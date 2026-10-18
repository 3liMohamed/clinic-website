<?php

namespace App\Filament\Resources\Users;

use App\Actions\User\UpdateUserPermissions;
use App\Filament\Resources\Users\Pages\ManageUsers;
use App\Filament\Resources\Users\Pages\PermissionForm;
use App\Models\User;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    use SoftDeletes;

    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::UserGroup;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getPluralModelLabel(): string
    {
        return __('system.user.navigation');
    }
    public static function getModelLabel(): string
    {
        return __('system.user.model');
    }

    public static function getNavigationGroup(): string
    {
        return __('system.settings.group');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('avatar')
                    ->image()
                    ->imageEditor()
                    ->avatar()
                    ->default('avatars/default.png')
                    ->disk('images')
                    ->directory('avatars')
                    ->maxSize(2048)
                    ->columnSpan(12)
                    ->extraAttributes([
                        'style' => 'margin: 0 auto;',
                    ])
                    ->label(__('system.field.avatar')),

                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(6)
                    ->suffixIcon(Heroicon::User)
                    ->suffixIconColor('primary')
                    ->label(__('system.field.name')),

                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->suffixIcon(Heroicon::Envelope)
                    ->suffixIconColor('primary')
                    ->columnSpan(6)
                    ->label(__('system.field.email')),

                TextInput::make('phone')
                    ->tel()
                    ->required()
                    ->maxLength(15)
                    ->suffixIcon(Heroicon::Phone)
                    ->suffixIconColor('primary')
                    ->columnSpan(10)
                    ->label(__('system.field.phone')),

                Toggle::make('is_active')
                    ->onColor('success')
                    ->offColor('danger')
                    ->inline(false)
                    ->default(false)
                    ->columnSpan(2)
                    ->label(__('system.field.is_active')),
                TextInput::make('password')
                    ->password()
                    ->revealable()
                    ->dehydrateStateUsing(fn($state) => filled($state) ? Hash::make($state) : null)
                    ->dehydrated(fn($state) => filled($state))
                    ->required(fn(string $context) => $context === 'create')
                    ->maxLength(255)
                    ->columnSpan(6)
                    ->label(__('system.field.password')),

                TextInput::make('password_confirmation')
                    ->password()
                    ->revealable()
                    ->rule('required_with:password')
                    ->required(fn(string $context) => $context === 'create')
                    ->same('password')
                    ->dehydrated(false)
                    ->maxLength(255)
                    ->columnSpan(6)
                    ->label(__('system.field.password_confirmation')),
            ])
            ->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->contentGrid([
                'sm' => 1,
                'md' => 1,
                'lg' => 2,
                'xl' => 3,
            ])
            ->recordTitleAttribute('name')
            ->columns([
                Stack::make([
                    ImageColumn::make('avatar')
                        ->disk('images')
                        ->imageSize(100)
                        ->extraImgAttributes([
                            'class' => 'rounded-lg mx-auto border-1 border-primary-50 dark:border-primary-500/10 object-cover',
                        ])
                        ->extraAttributes([
                            'class' => 'flex justify-center pt-5',
                        ]),

                    TextColumn::make('name')
                        ->searchable()
                        ->weight('bold')
                        ->size('lg')
                        ->alignCenter()
                        ->extraAttributes(['class' => 'mt-2'])
                        ->label(__('system.field.name')),

                    TextColumn::make('is_active')
                        ->badge()
                        ->alignCenter()
                        ->formatStateUsing(fn($state) => $state ? __('system.field.active') : __('system.field.inactive'))
                        ->color(fn($state) => $state ? 'success' : 'danger')
                        ->label(__('system.field.is_active')),

                    Split::make([
                        TextColumn::make('email')
                            ->searchable()
                            ->icon(Heroicon::Envelope)
                            ->iconColor('primary')
                            ->color('gray')
                            ->size('sm')
                            ->alignCenter()
                            ->label(__('system.field.email')),

                        TextColumn::make('phone')
                            ->searchable()
                            ->icon(Heroicon::Phone)
                            ->iconColor('primary')
                            ->color('gray')
                            ->size('sm')
                            ->alignCenter()
                            ->label(__('system.field.phone')),
                    ])
                        ->from('md')
                        ->extraAttributes(['class' => 'py-2 mx-auto gap-1.5']),

                ])
                    ->space(1)
                    ->extraAttributes([
                        'class' => 'rounded-2xl border border-gray-100 dark:border-white/5 bg-white dark:bg-gray-900 shadow-sm hover:shadow-lg hover:-__late-y-0.5 __ition-all duration-200',
                    ]),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->iconButton()
                    ->extraAttributes(['class' => '!ms-auto']),
                EditAction::make()->iconButton(),
                DeleteAction::make()->iconButton(),
                ForceDeleteAction::make()->iconButton(),
                RestoreAction::make()->button(),
                Action::make('permissions')
                    ->authorize(fn(User $user, User $record) => $user->can('permission', $record))
                    ->iconButton()
                    ->icon(Heroicon::OutlinedShieldCheck)
                    ->color(Color::Emerald)
                    ->modalWidth(Width::FiveExtraLarge)
                    ->schema(fn(Schema $schema) => PermissionForm::configure($schema))
                    ->fillForm(fn(User $record) => $record->permissions ?? [])
                    ->action(function (array $data, User $record, $livewire) {
                        app(UpdateUserPermissions::class)->handle($record, $data);
                        if (auth()->id() === $record->id) {
                            $livewire->dispatch('refresh-sidebar');
                            $livewire->dispatch('refresh-topbar');
                        }
                    })
                    ->extraAttributes(['class' => '!me-auto'])
                    ->label(__('system.action.permissions')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageUsers::route('/'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
