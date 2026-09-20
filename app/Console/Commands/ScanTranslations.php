<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class ScanTranslations extends Command
{
    /**
     * Command name and signature.
     *
     * php artisan translations:scan
     * php artisan translations:scan --dry-run
     * php artisan translations:scan --locales=ar,en,fr
     */
    protected $signature = 'translations:scan
        {--dry-run : Show the result without modifying any file}
        {--locales=ar,en : Locales to update, comma separated}';

    protected $description = 'Scans the codebase for translation keys (__, trans, @lang, Lang::get) and registers missing ones in lang/{locale}';

    /** @var array<string,array> */
    protected array $report = [
        'no_group' => [],
        'namespaced' => [],
        'manual_merge' => [],
        'appended' => [],
    ];

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $locales = array_filter(array_map('trim', explode(',', (string) $this->option('locales'))));

        $scanDirs = [
            app_path(),
            resource_path(),
            base_path('routes'),
        ];
        $extensions = ['php', 'blade.php'];
        $langDir = base_path('lang'); // Older Laravel: resource_path('lang')

        if ($dryRun) {
            $this->warn('Dry-run mode: no file will be modified, this is a preview only.');
        }

        $files = $this->scanDirectory($scanDirs, $extensions);
        $rawKeys = [];
        foreach ($files as $file) {
            $content = file_get_contents($file);
            foreach ($this->extractKeys($content) as $key) {
                $rawKeys[$key] = true;
            }
        }
        $rawKeys = array_keys($rawKeys);
        sort($rawKeys);

        $this->info('Scanned ' . count($files) . ' file(s), found ' . count($rawKeys) . ' key(s).');
        $this->newLine();

        $byGroup = []; // [group => [rest => fullKey]]

        foreach ($rawKeys as $fullKey) {
            $parsed = $this->parseKey($fullKey);

            if ($parsed['namespace'] !== null) {
                $this->report['namespaced'][] = $fullKey;
                continue;
            }

            if ($parsed['group'] === null) {
                $this->report['no_group'][] = $fullKey;
                continue;
            }

            $byGroup[$parsed['group']][$parsed['rest']] = $fullKey;
        }

        foreach ($locales as $locale) {
            $localeDir = $langDir . '/' . $locale;
            if (!$dryRun && !is_dir($localeDir)) {
                mkdir($localeDir, 0755, true);
            }

            foreach ($byGroup as $group => $restToFullKey) {
                $this->processGroup($localeDir, $group, $restToFullKey, $dryRun);
            }
        }

        $this->printReport();

        $this->info('Done ✅');

        return self::SUCCESS;
    }

    protected function processGroup(string $localeDir, string $group, array $restToFullKey, bool $dryRun): void
    {
        $langFile = $localeDir . '/' . $group . '.php';
        $fileExists = file_exists($langFile);

        // Brand-new file: nothing to preserve, just write the whole tree.
        if (!$fileExists) {
            $tree = [];
            foreach ($restToFullKey as $rest => $fullKey) {
                $this->setNested($tree, $rest, $this->leafValue($rest));
            }

            if ($dryRun) {
                $this->report['appended'][] = "$langFile (new file - " . count($restToFullKey) . ' key(s)) [dry-run]';
            } else {
                file_put_contents($langFile, "<?php\n\nreturn " . $this->exportArray($tree) . ";\n");
                $this->report['appended'][] = "$langFile (new file with " . count($restToFullKey) . ' key(s))';
            }

            return;
        }

        // Existing file: load it to know which keys already exist (semantic check).
        $existing = [];
        $broken = false;
        try {
            $loaded = @include $langFile;
        } catch (\Throwable $e) {
            $this->error("⚠ File $langFile looks corrupted or is not a valid PHP array ({$e->getMessage()}) — skipping it so the command can continue with the rest.");
            $loaded = null;
            $broken = true;
        }
        if (is_array($loaded)) {
            $existing = $loaded;
        } elseif ($loaded !== null) {
            $this->warn("⚠ $langFile exists but does not return an array — please review it manually.");
            $broken = true;
        }

        if ($broken) {
            $this->report['manual_merge'][$langFile][] = '[This file is corrupted - fix it manually or restore from git first]';
            return;
        }

        $missing = [];
        foreach ($restToFullKey as $rest => $fullKey) {
            if (!$this->nestedHasKey($existing, $rest)) {
                $missing[$rest] = $fullKey;
            }
        }

        if (empty($missing)) {
            return; // nothing to do for this group
        }

        if ($dryRun) {
            $this->report['appended'][] = "$langFile (" . implode(', ', array_keys($missing)) . ') [dry-run]';
            return;
        }

        $content = file_get_contents($langFile);
        $appliedKeys = [];

        foreach ($missing as $rest => $fullKey) {
            $segments = explode('.', $rest);
            $newContent = $this->insertKeyIntoContent($content, $segments, $this->leafValue($rest));

            if ($newContent === null) {
                // Could not safely locate an insertion point (unusual file structure) - report for manual merge.
                $this->report['manual_merge'][$langFile][] = $fullKey;
                continue;
            }

            $content = $newContent;
            $appliedKeys[] = $rest;
        }

        if (!empty($appliedKeys)) {
            file_put_contents($langFile, $content);
            $this->report['appended'][] = "$langFile (" . implode(', ', $appliedKeys) . ')';
        }
    }

    protected function printReport(): void
    {
        if (!empty($this->report['appended'])) {
            $this->info('✔ Auto-added:');
            foreach ($this->report['appended'] as $line) {
                $this->line("  - $line");
            }
            $this->newLine();
        }

        if (!empty($this->report['manual_merge'])) {
            $this->warn('⚠ Needs manual merge (could not safely locate an insertion point):');
            foreach ($this->report['manual_merge'] as $file => $keys) {
                $this->line("  File: $file");
                foreach (array_unique($keys) as $k) {
                    $this->line("    - $k");
                }
            }
            $this->newLine();
        }

        if (!empty($this->report['no_group'])) {
            $this->warn('⚠ Keys with no dot (you need to decide which file they belong to):');
            foreach ($this->report['no_group'] as $k) {
                $this->line("  - $k");
            }
            $this->newLine();
        }

        if (!empty($this->report['namespaced'])) {
            $this->warn('⚠ Namespaced (package) keys - need a dedicated path (lang/vendor/{namespace}):');
            foreach ($this->report['namespaced'] as $k) {
                $this->line("  - $k");
            }
            $this->newLine();
        }
    }

    // ---------------- Scanning & key parsing ----------------

    protected function scanDirectory(array $dirs, array $extensions): array
    {
        $files = [];
        foreach ($dirs as $dir) {
            if (!is_dir($dir)) {
                continue;
            }
            $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
            foreach ($rii as $file) {
                if ($file->isDir()) {
                    continue;
                }
                $path = $file->getPathname();
                foreach ($extensions as $ext) {
                    if (str_ends_with($path, '.' . $ext)) {
                        $files[] = $path;
                        break;
                    }
                }
            }
        }
        return $files;
    }

    protected function extractKeys(string $content): array
    {
        $keys = [];
        $patterns = [
            // __('key') | trans('key') | Lang::get('key') | Lang::has('key') | trans_choice('key')
            '/\b(?:__|trans|trans_choice|Lang::get|Lang::has|Lang::choice)\(\s*([\'"])((?:(?!\1).)*)\1/',
            // @lang('key')
            '/@lang\(\s*([\'"])((?:(?!\1).)*)\1/',
        ];
        foreach ($patterns as $pattern) {
            if (preg_match_all($pattern, $content, $matches)) {
                foreach ($matches[2] as $key) {
                    $key = trim($key);
                    if ($key !== '') {
                        $keys[] = $key;
                    }
                }
            }
        }
        return $keys;
    }

    /**
     * Breaks a raw key into its parts:
     * - namespace (if it contains ::)   e.g. package::group.key
     * - group (the file name)           first segment after the namespace, before the first dot
     * - rest (the remaining path inside the file)
     */
    protected function parseKey(string $rawKey): array
    {
        $namespace = null;
        $remaining = $rawKey;

        if (strpos($rawKey, '::') !== false) {
            [$namespace, $remaining] = explode('::', $rawKey, 2);
        }

        if (strpos($remaining, '.') === false) {
            return ['namespace' => $namespace, 'group' => null, 'rest' => $remaining];
        }

        [$group, $rest] = explode('.', $remaining, 2);
        return ['namespace' => $namespace, 'group' => $group, 'rest' => $rest];
    }

    /**
     * The last segment of the path — used as the default placeholder value
     * instead of the full key. Example: rest = "field.youtube" -> "youtube"
     */
    protected function leafValue(string $path): string
    {
        $segments = explode('.', $path);
        return (string) end($segments);
    }

    protected function nestedHasKey(array $array, string $path): bool
    {
        $ref = $array;
        foreach (explode('.', $path) as $segment) {
            if (!is_array($ref) || !array_key_exists($segment, $ref)) {
                return false;
            }
            $ref = $ref[$segment];
        }
        return true;
    }

    protected function setNested(array &$array, string $path, $value): void
    {
        $segments = explode('.', $path);
        $ref = &$array;
        foreach ($segments as $i => $segment) {
            if ($i === count($segments) - 1) {
                if (!array_key_exists($segment, $ref)) {
                    $ref[$segment] = $value;
                }
            } else {
                if (!isset($ref[$segment]) || !is_array($ref[$segment])) {
                    $ref[$segment] = [];
                }
                $ref = &$ref[$segment];
            }
        }
    }

    protected function exportArray(array $array, int $indent = 1): string
    {
        $pad = str_repeat('    ', $indent);
        $padClose = str_repeat('    ', max(0, $indent - 1));
        $out = "[\n";
        foreach ($array as $key => $value) {
            $out .= $pad . var_export($key, true) . ' => ';
            $out .= is_array($value) ? $this->exportArray($value, $indent + 1) : var_export($value, true);
            $out .= ",\n";
        }
        $out .= $padClose . ']';
        return $out;
    }

    // ---------------- Precise in-place text insertion ----------------

    /**
     * Finds the index of the ']' that matches the '[' at $openPos, while
     * ignoring brackets that appear inside quoted strings. Returns null if
     * no balanced match is found (unusual/unsupported file structure).
     */
    protected function findMatchingBracket(string $text, int $openPos): ?int
    {
        $depth = 0;
        $len = strlen($text);
        $inString = false;
        $quoteChar = '';

        for ($i = $openPos; $i < $len; $i++) {
            $ch = $text[$i];

            if ($inString) {
                if ($ch === '\\') {
                    $i++; // skip escaped character
                    continue;
                }
                if ($ch === $quoteChar) {
                    $inString = false;
                }
                continue;
            }

            if ($ch === "'" || $ch === '"') {
                $inString = true;
                $quoteChar = $ch;
                continue;
            }

            if ($ch === '[') {
                $depth++;
            } elseif ($ch === ']') {
                $depth--;
                if ($depth === 0) {
                    return $i;
                }
            }
        }

        return null;
    }

    /**
     * Locates the array literal assigned to a given quoted key within $text
     * (e.g. 'field' => [ ... ]). Returns the open/close bracket offsets
     * (relative to $text) or null if the key isn't found or its value
     * isn't an array literal.
     */
    protected function locateKeyArray(string $text, string $key): ?array
    {
        $pattern = '/([\'"])' . preg_quote($key, '/') . '\1\s*=>\s*\[/';
        if (!preg_match($pattern, $text, $m, PREG_OFFSET_CAPTURE)) {
            return null;
        }

        $openPos = $m[0][1] + strlen($m[0][0]) - 1; // position of '['
        $closePos = $this->findMatchingBracket($text, $openPos);
        if ($closePos === null) {
            return null;
        }

        return ['open' => $openPos, 'close' => $closePos];
    }

    /**
     * Inserts new entries right before the closing bracket at $closePos,
     * preserving everything else in the file untouched.
     */
    protected function spliceEntries(string $content, int $closePos, int $itemsDepth, array $entries): string
    {
        $before = substr($content, 0, $closePos);
        $after = substr($content, $closePos); // starts with ']'

        $trimmedBefore = rtrim($before);
        $needsComma = $trimmedBefore !== '' && !str_ends_with($trimmedBefore, ',') && !str_ends_with($trimmedBefore, '[');

        $indent = str_repeat('    ', $itemsDepth);
        $addition = '';
        foreach ($entries as $key => $value) {
            $addition .= "\n" . $indent . var_export($key, true) . ' => ';
            $addition .= is_array($value) ? $this->exportArray($value, $itemsDepth + 1) : var_export($value, true);
            $addition .= ',';
        }

        return $trimmedBefore . ($needsComma ? ',' : '') . $addition . "\n" . str_repeat('    ', max(0, $itemsDepth - 1)) . $after;
    }

    /**
     * Recursively walks down the array literal in $content to insert a value
     * at the given dot-path segments, creating any missing sub-arrays inline
     * as it goes, without touching unrelated parts of the file.
     */
    protected function insertAtLevel(string $content, int $open, int $close, array $segments, $value, int $itemsDepth): ?string
    {
        $key = $segments[0];
        $remaining = array_slice($segments, 1);

        if (empty($remaining)) {
            return $this->spliceEntries($content, $close, $itemsDepth, [$key => $value]);
        }

        $subScope = substr($content, $open + 1, $close - $open - 1);
        $located = $this->locateKeyArray($subScope, $key);

        if ($located === null) {
            // The whole branch is missing - insert it as one new nested entry.
            $tree = [];
            $this->setNested($tree, implode('.', $remaining), $value);
            return $this->spliceEntries($content, $close, $itemsDepth, [$key => $tree]);
        }

        $childOpen = $open + 1 + $located['open'];
        $childClose = $open + 1 + $located['close'];

        return $this->insertAtLevel($content, $childOpen, $childClose, $remaining, $value, $itemsDepth + 1);
    }

    /**
     * Entry point: finds the top-level "return [ ... ];" array and inserts
     * the given dot-path key into it. Returns the new file content, or null
     * if the file structure isn't recognized (falls back to manual merge).
     */
    protected function insertKeyIntoContent(string $content, array $segments, $value): ?string
    {
        if (!preg_match('/return\s*\[/', $content, $m, PREG_OFFSET_CAPTURE)) {
            return null;
        }

        $openPos = $m[0][1] + strlen($m[0][0]) - 1; // position of '['
        $closePos = $this->findMatchingBracket($content, $openPos);
        if ($closePos === null) {
            return null;
        }

        return $this->insertAtLevel($content, $openPos, $closePos, $segments, $value, 1);
    }
}
