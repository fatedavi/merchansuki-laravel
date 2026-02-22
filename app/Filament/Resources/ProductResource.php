<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Filament\Resources\ProductResource\Widgets\ProductStats;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Filament\Support\Enums\Alignment;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Product Management';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Product Detail')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('category_id')
                                    ->relationship('category', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload(),
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', Str::slug($state))),
                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true),
                                Forms\Components\TextInput::make('weight')
                                    ->numeric()
                                    ->suffix('grams'),
                                Forms\Components\Textarea::make('description')
                                    ->columnSpanFull(),
                                Forms\Components\Toggle::make('is_active')
                                    ->label('Active Status')
                                    ->default(true),
                                Forms\Components\Toggle::make('is_featured')
                                    ->label('Featured Product')
                                    ->default(false),
                            ]),
                    ])->collapsible(),

                Forms\Components\Section::make('Images')
                    ->schema([
                        Forms\Components\Repeater::make('images')
                            ->relationship()
                            ->schema([
                                Forms\Components\FileUpload::make('image_path')
                                    ->image()
                                    ->imageEditor()
                                    ->disk('public')
                                    ->visibility('public')
                                    ->directory('products')
                                    ->required(),
                                Forms\Components\Toggle::make('is_primary')
                                    ->label('Primary Image')
                                    ->default(false),
                            ])
                            ->columns(2)
                            ->itemLabel(fn (array $state): ?string => $state['is_primary'] ? 'Primary Image' : 'Product Image')
                            ->defaultItems(1)
                            ->columnSpanFull(),
                    ])->collapsible(),

                Forms\Components\Section::make('Variants')
                    ->schema([
                        Forms\Components\Repeater::make('variants')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('variant_name')
                                    ->required()
                                    ->maxLength(150),
                                Forms\Components\TextInput::make('price')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required(),
                                Forms\Components\TextInput::make('stock')
                                    ->numeric()
                                    ->default(0)
                                    ->required(),
                                Forms\Components\FileUpload::make('image')
                                    ->image()
                                    ->imageEditor()
                                    ->disk('public')
                                    ->visibility('public')
                                    ->directory('variants'),
                                Forms\Components\Select::make('status')
                                    ->options([
                                        'active' => 'Active',
                                        'inactive' => 'Inactive',
                                    ])
                                    ->default('active')
                                    ->required(),
                            ])
                            ->columns(2)
                            ->defaultItems(1)
                            ->columnSpanFull(),
                    ])->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ViewColumn::make('product_images')
                    ->label('IMAGE')
                    ->view('filament.tables.columns.product-images')
                    ->toggleable(),

                Tables\Columns\ViewColumn::make('product_info')
                    ->label('PRODUCT')
                    ->view('filament.tables.columns.product-info')
                    ->grow()
                    ->toggleable(),

                Tables\Columns\ViewColumn::make('variant_imgs')
                    ->label('VAR IMAGE')
                    ->view('filament.tables.columns.variant-images')
                    ->width('80px')
                    ->toggleable(),

                Tables\Columns\ViewColumn::make('variant_names')
                    ->label('VAR NAME')
                    ->view('filament.tables.columns.variant-names')
                    ->width('280px')
                    ->toggleable(),

                Tables\Columns\ViewColumn::make('variant_stocks')
                    ->label('STOCK')
                    ->view('filament.tables.columns.variant-stocks')
                    ->width('120px')
                    ->toggleable(),

                Tables\Columns\ViewColumn::make('variant_prices')
                    ->label('PRICE')
                    ->view('filament.tables.columns.variant-prices')
                    ->width('180px')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('CATEGORY')
                    ->sortable()
                    ->searchable()
                    ->color('gray')
                    ->width('150px'),

                Tables\Columns\TextColumn::make('is_active')
                    ->label('STATUS')
                    ->badge()
                    ->color(fn (bool $state): string => $state ? 'success' : 'danger')
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Active' : 'Inactive')
                    ->width('100px'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name'),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->iconButton()
                    ->color('primary')
                    ->size('lg'),
                Tables\Actions\DeleteAction::make()
                    ->iconButton()
                    ->color('danger')
                    ->size('lg'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('activate')
                        ->action(fn (Builder $query) => $query->update(['is_active' => true]))
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation(),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->action(fn (Builder $query) => $query->update(['is_active' => false]))
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation(),
                    Tables\Actions\BulkAction::make('mark_as_featured')
                        ->action(fn (Builder $query) => $query->update(['is_featured' => true]))
                        ->icon('heroicon-o-star')
                        ->color('warning')
                        ->requiresConfirmation(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ImagesRelationManager::class,
            RelationManagers\VariantsRelationManager::class,
            RelationManagers\ReviewsRelationManager::class,
        ];
    }

    public static function getWidgets(): array
    {
        return [
            ProductStats::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])
            ->with(['category', 'variants', 'images'])
            ->withCount(['variants', 'reviews']);
    }
}