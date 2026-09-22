<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeModuleCommand extends Command
{
    protected $signature = 'make:module {name : اسم الموديل، زي Slide أو ScheduleItem}';

    protected $description = 'يعمل Model, Migration, Service, Policy, و Filament Resource دفعة واحدة';

    public function handle(): int
    {
        $name = Str::studly($this->argument('name'));
        $table = Str::snake(Str::pluralStudly($name));

        $useSoftDeletes = $this->choice(
            'الموديل ده Simple ولا SoftDelete؟',
            ['simple', 'softDelete'],
            0
        ) === 'softDelete';

        $this->makeModel($name, $useSoftDeletes);
        $this->makeMigration($name, $table, $useSoftDeletes);
        $this->makeService($name);
        $this->makePolicy($name);
        $this->makeResource($name);

        $this->info("تم إنشاء الموديول {$name} بنجاح.");
        $this->warn('متنساش تراجع الـ fillable, casts, translatable, والفورم في الـ Resource وتظبطهم على حسب احتياجك.');

        return self::SUCCESS;
    }

    protected function makeModel(string $name, bool $softDeletes): void
    {
        $path = app_path("Models/{$name}.php");

        if (File::exists($path)) {
            $this->warn("Model {$name} موجود بالفعل، اتخطى.");
            return;
        }

        $softDeleteImport = $softDeletes ? "use Illuminate\Database\Eloquent\SoftDeletes;\n" : '';
        $softDeleteTrait = $softDeletes ? ', SoftDeletes' : '';
        $timestamps = $softDeletes
            ? ''
            : "\n    public \$timestamps = false;\n";

        $stub = <<<PHP
                    <?php

                    namespace App\Models;

                    use App\Traits\HasAuditLog;
                    use Illuminate\Database\Eloquent\Builder;
                    use Illuminate\Database\Eloquent\Model;
                    {$softDeleteImport}use Spatie\Translatable\HasTranslations;

                    class {$name} extends Model
                    {
                        use HasTranslations, HasAuditLog{$softDeleteTrait};
                    {$timestamps}
                        protected \$fillable = [
                            'is_visible',
                            'order',
                        ];

                        protected function casts(): array
                        {
                            return [
                                'is_visible' => 'boolean',
                                'order' => 'integer',
                            ];
                        }

                        public array \$translatable = [
                            //
                        ];

                        protected static function booted(): void
                        {
                            static::saved(fn () => app(\App\Services\\{$name}Service::class)->flush());
                            static::deleted(fn () => app(\App\Services\\{$name}Service::class)->flush());
                        }

                        public function scopeVisible(Builder \$query): Builder
                        {
                            return \$query->where('is_visible', true);
                        }

                        public function scopeOrdered(Builder \$query): Builder
                        {
                            return \$query->orderBy('order');
                        }
                    }

                    PHP;

        File::put($path, $stub);
        $this->info("✔ Model: app/Models/{$name}.php");
    }

    protected function makeMigration(string $name, string $table, bool $softDeletes): void
    {
        $className = 'Create' . Str::studly($table) . 'Table';
        $fileName = date('Y_m_d_His') . '_create_' . $table . '_table.php';
        $path = database_path("migrations/{$fileName}");

        $softDeleteLine = $softDeletes ? "\n            \$table->softDeletes();" : '';

        $stub = <<<PHP
                    <?php

                    use Illuminate\Database\Migrations\Migration;
                    use Illuminate\Database\Schema\Blueprint;
                    use Illuminate\Support\Facades\Schema;

                    return new class extends Migration
                    {
                        public function up(): void
                        {
                            Schema::create('{$table}', function (Blueprint \$table) {
                                \$table->id();
                                \$table->boolean('is_visible')->default(false);
                                \$table->unsignedInteger('order')->default(0);{$softDeleteLine}
                            });
                        }

                        public function down(): void
                        {
                            Schema::dropIfExists('{$table}');
                        }
                    };

                    PHP;

        File::put($path, $stub);
        $this->info("✔ Migration: database/migrations/{$fileName}");
    }

    protected function makeService(string $name): void
    {
        $path = app_path("Services/{$name}Service.php");

        if (File::exists($path)) {
            $this->warn("Service {$name}Service موجود بالفعل، اتخطى.");
            return;
        }

        $cacheKey = Str::kebab(Str::pluralStudly($name)) . ':all-visible';

        $stub = <<<PHP
                    <?php

                    namespace App\Services;

                    use App\Models\\{$name};
                    use Illuminate\Database\Eloquent\Collection;
                    use Illuminate\Support\Facades\Cache;

                    class {$name}Service
                    {
                        protected const CACHE_KEY = '{$cacheKey}';

                        public function allVisible(): Collection
                        {
                            return Cache::rememberForever(self::CACHE_KEY, function () {
                                return {$name}::query()
                                    ->visible()
                                    ->ordered()
                                    ->get();
                            });
                        }

                        public function flush(): void
                        {
                            Cache::forget(self::CACHE_KEY);
                        }
                    }

                    PHP;

        File::put($path, $stub);
        $this->info("✔ Service: app/Services/{$name}Service.php");
    }

    protected function makePolicy(string $name): void
    {
        $this->call('make:policy', [
            'name' => "{$name}Policy",
            '--model' => $name,
        ]);
    }

    protected function makeResource(string $name): void
    {
        $this->call('make:filament-resource', [
            'name' => $name,
            '--generate' => true,
        ]);
    }
}
