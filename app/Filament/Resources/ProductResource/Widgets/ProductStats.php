<?php

namespace App\Filament\Resources\ProductResource\Widgets;

use App\Models\Product;
use App\Models\ProductVariant;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProductStats extends BaseWidget
{
   protected function getStats(): array
{
    return [
        Stat::make('Total Product', Product::count())
            ->description('Total products in catalog')
            ->color('info'),
            
        Stat::make('Active Product', Product::where('is_active', true)->count())
            ->description('Products currently visible')
            ->color('success'),
            
        Stat::make('Featured Product', Product::where('is_featured', true)->count())
            ->description('Highlighted products')
            ->color('warning'),
            
        Stat::make('Total Variant', 
            // Hanya hitung variant dari product yang tidak dihapus
            ProductVariant::whereHas('product', function ($query) {
                $query->whereNull('deleted_at');
            })->count()
        )
            ->description('Total product variations')
            ->color('primary'),
    ];
}
}
