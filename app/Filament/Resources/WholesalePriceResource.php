<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WholesalePriceResource\Pages;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\VariantWholesalePrice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class WholesalePriceResource extends Resource
{
    protected static ?string $model = VariantWholesalePrice::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationGroup = 'Product Management';

    protected static ?string $recordTitleAttribute = 'min_quantity';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Wholesale Price Detail')
                    ->schema([
                        Forms\Components\Select::make('product_id')
                            ->label('Product')
                            ->options(fn () => Product::where('is_active', true)->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn (callable $set) => $set('product_variant_id', null)),
                        Forms\Components\Select::make('product_variant_id')
                            ->label('Product Variant')
                            ->visible(fn (callable $get) => $get('product_id') !== null)
                            ->options(function (callable $get) {
                                $productId = $get('product_id');
                                if (!$productId) {
                                    return [];
                                }
                                return ProductVariant::where('product_id', $productId)
                                    ->pluck('variant_name', 'id');
                            })
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\TextInput::make('min_quantity')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->label('Minimum Quantity'),
                        Forms\Components\TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->label('Wholesale Price'),
                    ])->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('variant.product.name')
                    ->label('Product')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('variant.variant_name')
                    ->label('Variant')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('min_quantity')
                    ->label('Min Qty')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('product_id')
                    ->label('Product')
                    ->options(fn () => Product::where('is_active', true)->pluck('name', 'id'))
                    ->query(function (Builder $query, array $data) {
                        if ($data['value']) {
                            $query->whereHas('variant', function ($q) use ($data) {
                                $q->where('product_id', $data['value']);
                            });
                        }
                    }),
                Tables\Filters\SelectFilter::make('variant_id')
                    ->label('Variant')
                    ->options(fn () => ProductVariant::pluck('variant_name', 'id')),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->iconButton(),
                Tables\Actions\DeleteAction::make()->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWholesalePrices::route('/'),
            'create' => Pages\CreateWholesalePrice::route('/create'),
            'edit' => Pages\EditWholesalePrice::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['variant', 'variant.product']);
    }
}
