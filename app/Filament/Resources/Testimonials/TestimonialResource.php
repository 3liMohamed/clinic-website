<?php

namespace App\Filament\Resources\Testimonials;

use App\Filament\Resources\Testimonials\Pages\ManageTestimonials;
use App\Models\Testimonial;
use App\Services\TestimonialService;
use BackedEnum;
use Filament\Actions\ActionGroup;
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
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class TestimonialResource extends Resource
{
    use SoftDeletes, Translatable;

    protected static ?string $model = Testimonial::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getPluralModelLabel(): string
    {
        return __('system.testimonial.navigation');
    }
    public static function getModelLabel(): string
    {
        return __('system.testimonial.model');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('avatar')
                    ->image()
                    ->imageEditor()
                    ->default('default.webp')
                    ->disk('images')
                    ->directory('testimonials')
                    ->maxSize(2048)
                    ->columnSpanFull()
                    ->label(__('system.field.avatar')),

                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(10)
                    ->label(__('system.field.name')),

                Toggle::make('is_visible')
                    ->default(true)
                    ->onColor('success')
                    ->offColor('danger')
                    ->inline(false)
                    ->columnSpan(2)
                    ->label(__('system.field.is_visible')),

                Textarea::make('content')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull()
                    ->label(__('system.field.content')),

            ])->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->reorderable('order')
            ->defaultSort('order')
            ->afterReordering(fn(array $order) => app(TestimonialService::class)->flush())
            ->columns([
                ImageColumn::make('avatar')
                    ->disk('images')
                    ->imageSize(60)
                    ->circular()
                    ->label(__('system.field.avatar')),

                TextColumn::make('name')
                    ->searchable()
                    ->description(fn(Testimonial $record): string => $record->content)
                    ->label(__('system.field.name')),

                IconColumn::make('is_visible')
                    ->boolean()
                    ->label(__('system.field.is_visible')),
            ])
            ->headerActions([
                LocaleSwitcher::make(),
                CreateAction::make(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                    ForceDeleteAction::make(),
                    RestoreAction::make(),
                ]),
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
            'index' => ManageTestimonials::route('/'),
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
