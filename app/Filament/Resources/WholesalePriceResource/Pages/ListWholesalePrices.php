<?php

namespace App\Filament\Resources\WholesalePriceResource\Pages;

use App\Filament\Resources\WholesalePriceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWholesalePrices extends ListRecords
{
    protected static string $resource = WholesalePriceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
