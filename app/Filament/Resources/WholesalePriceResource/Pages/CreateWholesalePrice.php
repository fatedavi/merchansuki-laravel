<?php

namespace App\Filament\Resources\WholesalePriceResource\Pages;

use App\Filament\Resources\WholesalePriceResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateWholesalePrice extends CreateRecord
{
    protected static string $resource = WholesalePriceResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        unset($data['product_id']);
        return $data;
    }
}
