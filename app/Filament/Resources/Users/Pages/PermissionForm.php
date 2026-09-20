<?php

namespace App\Filament\Resources\Users\Pages;


use App\Models\User;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class PermissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components(function (User $user): array {
            $permissions = $user->permissions ?? [];
            $merged = self::mergePermissions(User::getPermissionsSchema(), $permissions);

            $sections = self::buildSections($merged);
            array_unshift($sections, self::buildGlobalToggle($merged));

            return $sections;
        });
    }

    protected static function buildSections(array $merged): array
    {
        $sections = [];

        foreach ($merged as $section => $permissionList) {
            $sections[] = Section::make(ucwords(str_replace(['_', '-'], ' ', $section)))
                ->schema(self::buildPermissionCheckboxes($section, $permissionList))
                ->collapsed()
                ->columns(count($permissionList) + 1);
        }

        return $sections;
    }

    protected static function buildGlobalToggle(array $merged): Section
    {
        $allEnabled = collect($merged)
            ->flatten()
            ->every(fn ($v) => $v === true);

        return Section::make('Enable All')
            ->schema([
                Toggle::make('_all')
                    ->label(fn (bool $state) => $state ? 'Enable All' : 'Disable All')
                    ->afterStateHydrated(fn (Set $set) => $set('_all', $allEnabled))
                    ->default($allEnabled)
                    ->onIcon(Heroicon::Check)
                    ->offIcon(Heroicon::XMark)
                    ->offColor('danger')
                    ->dehydrated(false)
                    ->live()
                    ->afterStateUpdated(function (bool $state, Set $set) use ($merged) {
                        foreach ($merged as $section => $items) {
                            $set("{$section}._enabled", $state);
                            foreach ($items as $key => $_) {
                                $set("{$section}.{$key}", $state);
                            }
                        }
                    }),
            ]);
    }

    protected static function buildPermissionCheckboxes(string $section, array $permissionList): array
    {
        $checkboxes = [];

        $allSectionEnabled = collect($permissionList)->every(fn ($v) => (bool) $v === true);

        $checkboxes[] = Toggle::make("{$section}._enabled")
            ->label(fn (bool $state) => $state ? 'Disable' : 'Enable')
            ->afterStateHydrated(fn (Set $set) => $set("{$section}._enabled", $allSectionEnabled))
            ->onIcon(Heroicon::Check)
            ->offIcon(Heroicon::XMark)
            ->offColor('danger')
            ->dehydrated(false)
            ->live()
            ->columnSpan(1)
            ->afterStateUpdated(function (bool $state, Set $set, Get $get) use ($section, $permissionList) {
                foreach ($permissionList as $key => $_) {
                    $set("{$section}.{$key}", $state);
                }
                self::updateGlobalToggle($get, $set);
            });

        foreach ($permissionList as $key => $isChecked) {
            $checkboxes[] = Checkbox::make("{$section}.{$key}")
                ->label(ucwords(str_replace(['_', '-'], ' ', $key)))
                ->default($isChecked)
                ->live()
                ->columnSpan(1)
                ->afterStateUpdated(function (Get $get, Set $set) use ($section) {
                    $currentSection = $get($section) ?? [];

                    unset($currentSection['_enabled']);

                    $isAllChecked = collect($currentSection)->every(fn ($val) => (bool) $val === true);

                    $set("{$section}._enabled", $isAllChecked);

                    self::updateGlobalToggle($get, $set);
                });
        }

        return $checkboxes;
    }

    protected static function mergePermissions(array $base, array $dynamic): array
    {
        $result = [];

        foreach ($base as $section => $basePerms) {
            $dynamicPerms = $dynamic[$section] ?? [];
            $finalPerms = [];

            foreach ($basePerms as $key => $defaultValue) {
                $finalPerms[$key] = array_key_exists($key, $dynamicPerms)
                    ? $dynamicPerms[$key]
                    : $defaultValue;
            }

            $result[$section] = $finalPerms;
        }

        return $result;
    }

    protected static function updateGlobalToggle(Get $get, Set $set): void
    {
        $allData = $get('') ?? [];

        $isEverythingEnabled = true;

        foreach ($allData as $sectionName => $values) {
            if (is_array($values)) {
                $tempValues = $values;
                unset($tempValues['_enabled']);

                if (collect($tempValues)->contains(fn ($v) => (bool) $v === false)) {
                    $isEverythingEnabled = false;
                    break;
                }
            }
        }

        $set('_all', $isEverythingEnabled);
    }
}

