<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MigrateFreshSafe extends Command
{
    /**
     * php artisan migrate:fresh-safe
     * php artisan migrate:fresh-safe --seed
     * php artisan migrate:fresh-safe --except=cache,sessions
     * php artisan migrate:fresh-safe --restore-only=2025_09_09_120000   (restore a previous backup without running fresh)
     */
    protected $signature = 'migrate:fresh-safe
        {--seed}
        {--path=}
        {--except=* : Tables to exclude from backup/restore}
        {--chunk=1000 : Batch size for reading/inserting}
        {--keep-backup : Do not delete the backup after a successful restore}
        {--restore-only= : Name of a previous backup folder to restore without running fresh}';

    protected $description = 'Backs up all table data before migrate:fresh, then restores it afterwards with real default/nullable matching';

    protected array $ignoreTables = ['migrations'];
    protected int $chunkSize = 1000;
    protected string $disk = 'local';
    protected string $backupRoot = 'migrate-fresh-safe';
    protected string $backupDir = '';

    /** Final report per table */
    protected array $report = [];

    public function handle(): int
    {
        $this->chunkSize = max(50, (int) $this->option('chunk'));
        $except = array_merge($this->ignoreTables, $this->option('except') ?: []);

        // ---------- Restore-only mode (from an existing backup) ----------
        if ($restoreOnly = $this->option('restore-only')) {
            $this->backupDir = $this->backupRoot . '/' . $restoreOnly;

            if (! Storage::disk($this->disk)->exists($this->backupDir)) {
                $this->error("No backup found named: {$this->backupDir}");
                return self::FAILURE;
            }

            $tables = collect(Storage::disk($this->disk)->files($this->backupDir . '/meta'))
                ->map(fn ($p) => pathinfo($p, PATHINFO_FILENAME))
                ->toArray();

            $this->restoreAll($tables);
            $this->printSummary();

            return self::SUCCESS;
        }

        // ---------- Normal mode: backup -> fresh -> restore ----------
        $tables = $this->getAllTables($except);

        if (empty($tables)) {
            $this->warn('No tables found, running plain migrate:fresh without a backup.');
            return $this->runFresh() ? self::SUCCESS : self::FAILURE;
        }

        $this->backupDir = $this->backupRoot . '/' . now()->format('Y_m_d_His');
        Storage::disk($this->disk)->makeDirectory($this->backupDir . '/data');
        Storage::disk($this->disk)->makeDirectory($this->backupDir . '/meta');

        $this->info("Backing up data into: {$this->backupDir}");
        foreach ($tables as $table) {
            $this->backupTable($table);
        }

        $this->info('Running migrate:fresh...');
        $freshOk = $this->runFresh();

        if (! $freshOk) {
            $this->error('migrate:fresh failed! Stopping here, the backup will not be touched.');
            $this->warn("Backup is still saved at: storage/app/{$this->backupDir}");
            $this->warn('Once the issue is fixed, run: php artisan migrate:fresh-safe --restore-only=' . basename($this->backupDir));
            return self::FAILURE;
        }

        $this->info('Restoring data...');
        $this->restoreAll($tables);

        if ($this->option('seed')) {
            $this->info('Seeding...');
            Artisan::call('db:seed', ['--force' => true], $this->output);
        }

        $this->printSummary();

        $hasFailures = collect($this->report)->contains(fn ($r) => ($r['failed'] ?? 0) > 0);

        if (! $hasFailures && ! $this->option('keep-backup')) {
            Storage::disk($this->disk)->deleteDirectory($this->backupDir);
            $this->line('Temporary backup removed (use --keep-backup to retain it).');
        } else {
            $this->warn("Backup kept at: storage/app/{$this->backupDir}");
        }

        return $hasFailures ? self::FAILURE : self::SUCCESS;
    }

    protected function runFresh(): bool
    {
        $params = ['--force' => true];
        if ($this->option('path')) {
            $params['--path'] = $this->option('path');
        }

        $exitCode = Artisan::call('migrate:fresh', $params, $this->output);

        return $exitCode === 0;
    }

    protected function getAllTables(array $ignore): array
    {
        $tables = collect(Schema::getTables())->pluck('name')->toArray();

        return array_values(array_filter($tables, fn ($t) => ! in_array($t, $ignore, true)));
    }

    // =========================================================================
    // Backup
    // =========================================================================

    protected function backupTable(string $table): void
    {
        if (! Schema::hasTable($table)) {
            return;
        }

        $meta = $this->getColumnsMeta($table);
        Storage::disk($this->disk)->put(
            "{$this->backupDir}/meta/{$table}.json",
            json_encode($meta, JSON_UNESCAPED_UNICODE)
        );

        $pk = $this->primaryKeyColumn($meta);
        $path = Storage::disk($this->disk)->path("{$this->backupDir}/data/{$table}.ndjson");
        $handle = fopen($path, 'w');

        $count = 0;
        $query = DB::table($table);

        $writeChunk = function ($rows) use ($handle, &$count) {
            foreach ($rows as $row) {
                fwrite($handle, json_encode((array) $row, JSON_UNESCAPED_UNICODE) . "\n");
                $count++;
            }
        };

        // chunkById when a simple primary key exists, otherwise a plain offset-based chunk
        if ($pk) {
            $query->orderBy($pk)->chunkById($this->chunkSize, $writeChunk, $pk);
        } else {
            $query->orderBy(DB::raw('1'))->chunk($this->chunkSize, $writeChunk);
        }

        fclose($handle);

        $this->report[$table]['backed_up'] = $count;
        $this->line("  - {$table}: {$count} rows backed up");
    }

    // =========================================================================
    // Restore
    // =========================================================================

    protected function restoreAll(array $tables): void
    {
        Schema::disableForeignKeyConstraints();

        try {
            foreach ($tables as $table) {
                $this->restoreTable($table);
            }
        } finally {
            Schema::enableForeignKeyConstraints();
        }
    }

    protected function restoreTable(string $table): void
    {
        $metaPath = "{$this->backupDir}/meta/{$table}.json";
        $dataPath = "{$this->backupDir}/data/{$table}.ndjson";

        if (! Storage::disk($this->disk)->exists($metaPath)) {
            return;
        }

        if (! Schema::hasTable($table)) {
            $this->warn("  ! Table {$table} no longer exists after fresh, skipping");
            $this->report[$table]['skipped'] = true;
            return;
        }

        $oldMeta = json_decode(Storage::disk($this->disk)->get($metaPath), true);
        $newMeta = $this->getColumnsMeta($table);

        $compat = $this->checkCompatibility($oldMeta, $newMeta);
        $this->printCompatibility($table, $compat);

        $strategies = $this->buildColumnStrategies($newMeta);
        $newColumns = array_column($newMeta, 'name');
        $pkColumn = $this->primaryKeyColumn($newMeta);
        $pkFamily = null;
        foreach ($newMeta as $c) {
            if ($c['name'] === $pkColumn) {
                $pkFamily = $c['family'];
                break;
            }
        }
        $pkIsInt = $pkColumn && $pkFamily === 'integer';

        $absolutePath = Storage::disk($this->disk)->path($dataPath);

        if (! file_exists($absolutePath)) {
            $this->report[$table]['restored'] = 0;
            $this->report[$table]['failed'] = 0;
            return;
        }

        $handle = fopen($absolutePath, 'r');
        $buffer = [];
        $restored = 0;
        $failed = 0;
        $maxPk = null;

        $flush = function () use (&$buffer, $table, &$restored, &$failed) {
            if (empty($buffer)) {
                return;
            }
            [$ok, $bad] = $this->insertChunk($table, $buffer);
            $restored += $ok;
            $failed += $bad;
            $buffer = [];
        };

        while (($line = fgets($handle)) !== false) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            $oldRow = json_decode($line, true);
            $newRow = [];

            foreach ($newColumns as $col) {
                if (array_key_exists($col, $oldRow)) {
                    $newRow[$col] = $oldRow[$col];
                } else {
                    $newRow[$col] = $this->resolveStrategyValue($strategies[$col]);
                }
            }

            if ($pkIsInt && isset($newRow[$pkColumn]) && is_numeric($newRow[$pkColumn])) {
                $maxPk = max($maxPk ?? 0, (int) $newRow[$pkColumn]);
            }

            $buffer[] = $newRow;

            if (count($buffer) >= $this->chunkSize) {
                $flush();
            }
        }
        $flush();
        fclose($handle);

        $this->report[$table]['restored'] = $restored;
        $this->report[$table]['failed'] = $failed;
        $this->report[$table]['new_columns'] = $compat['added'];
        $this->report[$table]['dropped_columns'] = $compat['removed'];

        $line = "  - {$table}: {$restored} rows restored";
        if ($failed > 0) {
            $line .= " - {$failed} rows FAILED (see failed/{$table}.json)";
        }
        $this->line($line);

        if ($pkIsInt && $maxPk !== null) {
            $this->resetAutoIncrement($table, $pkColumn, $maxPk);
        }
    }

    /**
     * Inserts the whole batch at once; if it fails, falls back to inserting
     * row by row so we know exactly which rows failed and can log them
     * instead of failing silently.
     */
    protected function insertChunk(string $table, array $rows): array
    {
        try {
            DB::table($table)->insert($rows);
            return [count($rows), 0];
        } catch (\Throwable $e) {
            $ok = 0;
            $failedRows = [];

            foreach ($rows as $row) {
                try {
                    DB::table($table)->insert([$row]);
                    $ok++;
                } catch (\Throwable $rowError) {
                    $failedRows[] = ['row' => $row, 'error' => $rowError->getMessage()];
                }
            }

            if (! empty($failedRows)) {
                $this->appendFailedRows($table, $failedRows);
            }

            return [$ok, count($failedRows)];
        }
    }

    protected function appendFailedRows(string $table, array $failedRows): void
    {
        $path = "{$this->backupDir}/failed/{$table}.json";
        $existing = Storage::disk($this->disk)->exists($path)
            ? json_decode(Storage::disk($this->disk)->get($path), true)
            : [];

        Storage::disk($this->disk)->put(
            $path,
            json_encode(array_merge($existing, $failedRows), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        );
    }

    // =========================================================================
    // Column metadata (driver-aware): nullable / default / type / primary key
    // =========================================================================

    protected function getColumnsMeta(string $table): array
    {
        $driver = DB::connection()->getDriverName();

        $meta = match ($driver) {
            'mysql' => $this->columnsMetaMysql($table),
            'pgsql' => $this->columnsMetaPgsql($table),
            'sqlite' => $this->columnsMetaSqlite($table),
            default => $this->columnsMetaFallback($table),
        };

        foreach ($meta as &$col) {
            $col['family'] = $this->typeFamily($col['type']);
        }

        return $meta;
    }

    protected function columnsMetaMysql(string $table): array
    {
        $rows = DB::select(
            'SELECT COLUMN_NAME as name, IS_NULLABLE as is_nullable, COLUMN_DEFAULT as col_default,
                    DATA_TYPE as data_type, EXTRA as extra, COLUMN_KEY as col_key
             FROM information_schema.columns
             WHERE table_schema = DATABASE() AND table_name = ?
             ORDER BY ORDINAL_POSITION',
            [$table]
        );

        return array_map(fn ($r) => [
            'name' => $r->name,
            'nullable' => $r->is_nullable === 'YES',
            'default' => $r->col_default,
            'type' => $r->data_type,
            'primary' => $r->col_key === 'PRI',
            'auto_increment' => str_contains($r->extra ?? '', 'auto_increment'),
        ], $rows);
    }

    protected function columnsMetaPgsql(string $table): array
    {
        $rows = DB::select(
            "SELECT column_name as name, is_nullable, column_default as col_default, data_type
             FROM information_schema.columns
             WHERE table_schema = 'public' AND table_name = ?
             ORDER BY ordinal_position",
            [$table]
        );

        $pkCols = collect(DB::select(
            'SELECT a.attname as name
             FROM pg_index i
             JOIN pg_attribute a ON a.attrelid = i.indrelid AND a.attnum = ANY(i.indkey)
             WHERE i.indrelid = ?::regclass AND i.indisprimary',
            [$table]
        ))->pluck('name')->toArray();

        return array_map(fn ($r) => [
            'name' => $r->name,
            'nullable' => $r->is_nullable === 'YES',
            'default' => $r->col_default,
            'type' => $r->data_type,
            'primary' => in_array($r->name, $pkCols, true),
            'auto_increment' => str_contains($r->col_default ?? '', 'nextval('),
        ], $rows);
    }

    protected function columnsMetaSqlite(string $table): array
    {
        $rows = DB::select("PRAGMA table_info(\"{$table}\")");

        return array_map(fn ($r) => [
            'name' => $r->name,
            'nullable' => (int) $r->notnull === 0,
            'default' => $r->dflt_value,
            'type' => $r->type,
            'primary' => (int) $r->pk === 1,
            'auto_increment' => (int) $r->pk === 1 && strtolower($r->type) === 'integer',
        ], $rows);
    }

    /**
     * Weaker fallback for other drivers (e.g. sqlsrv): we don't have real
     * nullable/default info, so everything is treated as nullable=false,
     * default=null, and the type-based fallback decides the value.
     */
    protected function columnsMetaFallback(string $table): array
    {
        $this->warn("  ! Current driver is not fully supported for real defaults on table {$table}, using safe fallback values only.");

        $columns = Schema::getColumnListing($table);

        return array_map(function ($col) use ($table) {
            try {
                $type = Schema::getColumnType($table, $col);
            } catch (\Throwable $e) {
                $type = 'string';
            }

            return [
                'name' => $col,
                'nullable' => false,
                'default' => null,
                'type' => $type,
                'primary' => $col === 'id',
                'auto_increment' => $col === 'id',
            ];
        }, $columns);
    }

    protected function typeFamily(string $type): string
    {
        $type = strtolower($type);

        return match (true) {
            str_contains($type, 'int') => 'integer',
            str_contains($type, 'decimal'), str_contains($type, 'numeric'),
            str_contains($type, 'float'), str_contains($type, 'double'), str_contains($type, 'real') => 'numeric',
            str_contains($type, 'bool') => 'boolean',
            str_contains($type, 'json') => 'json',
            str_contains($type, 'datetime'), str_contains($type, 'timestamp') => 'datetime',
            $type === 'date' => 'date',
            str_contains($type, 'char'), str_contains($type, 'text'), str_contains($type, 'string') => 'string',
            default => 'string',
        };
    }

    protected function primaryKeyColumn(array $meta): ?string
    {
        $pks = array_values(array_filter($meta, fn ($c) => $c['primary']));

        return count($pks) === 1 ? $pks[0]['name'] : null;
    }

    // =========================================================================
    // Default value resolution
    // =========================================================================

    /**
     * Builds a strategy for each column once (instead of recomputing it
     * per row): ['mode' => 'null'|'default'|'fallback'|'uuid', 'value' => ...]
     */
    protected function buildColumnStrategies(array $newMeta): array
    {
        $strategies = [];

        foreach ($newMeta as $col) {
            if ($col['nullable']) {
                $strategies[$col['name']] = ['mode' => 'null'];
                continue;
            }

            if ($col['default'] !== null) {
                $strategies[$col['name']] = ['mode' => 'default', 'value' => $this->castDefault($col['default'], $col['family'])];
                continue;
            }

            if (str_contains(strtolower($col['name']), 'uuid')) {
                $strategies[$col['name']] = ['mode' => 'uuid'];
                continue;
            }

            $strategies[$col['name']] = ['mode' => 'fallback', 'value' => $this->fallbackForFamily($col['family'])];
        }

        return $strategies;
    }

    protected function castDefault($default, string $family)
    {
        // MySQL returns string defaults wrapped in quotes, e.g. 'active'
        if (is_string($default) && preg_match("/^'(.*)'$/", $default, $m)) {
            $default = $m[1];
        }

        $upper = strtoupper((string) $default);
        if (in_array($upper, ['CURRENT_TIMESTAMP', 'CURRENT_TIMESTAMP()', 'NOW()'], true)) {
            return now()->format('Y-m-d H:i:s');
        }

        return match ($family) {
            'integer' => (int) $default,
            'numeric' => (float) $default,
            'boolean' => (bool) ((int) $default),
            default => $default,
        };
    }

    protected function fallbackForFamily(string $family)
    {
        return match ($family) {
            'integer', 'numeric', 'boolean' => 0,
            'json' => '{}',
            'date' => now()->format('Y-m-d'),
            'datetime' => now()->format('Y-m-d H:i:s'),
            default => '',
        };
    }

    protected function resolveStrategyValue(array $strategy)
    {
        return match ($strategy['mode']) {
            'null' => null,
            'uuid' => (string) Str::uuid(),
            default => $strategy['value'],
        };
    }

    // =========================================================================
    // Compatibility report
    // =========================================================================

    protected function checkCompatibility(array $oldMeta, array $newMeta): array
    {
        $oldNames = array_column($oldMeta, 'name');
        $newNames = array_column($newMeta, 'name');
        $oldByName = collect($oldMeta)->keyBy('name');
        $newByName = collect($newMeta)->keyBy('name');

        $added = array_values(array_diff($newNames, $oldNames));
        $removed = array_values(array_diff($oldNames, $newNames));

        $typeChanged = [];
        foreach (array_intersect($oldNames, $newNames) as $name) {
            $oldFamily = $oldByName[$name]['family'] ?? null;
            $newFamily = $newByName[$name]['family'] ?? null;
            if ($oldFamily && $newFamily && $oldFamily !== $newFamily) {
                $typeChanged[] = "{$name} ({$oldFamily} -> {$newFamily})";
            }
        }

        return ['added' => $added, 'removed' => $removed, 'type_changed' => $typeChanged];
    }

    protected function printCompatibility(string $table, array $compat): void
    {
        if (! empty($compat['added'])) {
            $this->line('    + New columns filled with a default value: ' . implode(', ', $compat['added']));
        }
        if (! empty($compat['removed'])) {
            $this->line('    - Dropped columns (ignored from backup): ' . implode(', ', $compat['removed']));
        }
        if (! empty($compat['type_changed'])) {
            $this->warn('    ! Column type changed: ' . implode(', ', $compat['type_changed']));
        }
    }

    // =========================================================================
    // Auto increment reset
    // =========================================================================

    protected function resetAutoIncrement(string $table, string $pkColumn, int $maxValue): void
    {
        $driver = DB::connection()->getDriverName();

        try {
            match ($driver) {
                'mysql' => DB::statement("ALTER TABLE `{$table}` AUTO_INCREMENT = " . ($maxValue + 1)),
                'pgsql' => $this->resetSequencePgsql($table, $pkColumn, $maxValue),
                'sqlite' => DB::statement('UPDATE sqlite_sequence SET seq = ? WHERE name = ?', [$maxValue, $table]),
                'sqlsrv' => DB::statement("DBCC CHECKIDENT ('{$table}', RESEED, {$maxValue})"),
                default => null,
            };
        } catch (\Throwable $e) {
            $this->warn("    ! Could not reset auto_increment for table {$table}: " . $e->getMessage());
        }
    }

    protected function resetSequencePgsql(string $table, string $pkColumn, int $maxValue): void
    {
        $seq = DB::selectOne('SELECT pg_get_serial_sequence(?, ?) as seq', [$table, $pkColumn]);

        if ($seq && $seq->seq) {
            DB::statement('SELECT setval(?, ?)', [$seq->seq, $maxValue]);
        }
    }

    // =========================================================================
    // Summary
    // =========================================================================

    protected function printSummary(): void
    {
        if (empty($this->report)) {
            return;
        }

        $this->newLine();
        $this->info('Summary:');

        $rows = [];
        foreach ($this->report as $table => $r) {
            $rows[] = [
                $table,
                $r['backed_up'] ?? '-',
                $r['restored'] ?? '-',
                ($r['failed'] ?? 0) > 0 ? "FAILED: {$r['failed']}" : '0',
                ! empty($r['new_columns']) ? implode(', ', $r['new_columns']) : '-',
            ];
        }

        $this->table(['Table', 'Backed up', 'Restored', 'Failed', 'New columns'], $rows);

        $totalFailed = collect($this->report)->sum('failed');
        if ($totalFailed > 0) {
            $this->error("{$totalFailed} rows failed to restore. Details saved in: storage/app/{$this->backupDir}/failed/");
        }
    }
}
