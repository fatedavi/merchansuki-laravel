<?php

namespace App\Filament\Resources\WholesalePriceResource\Pages;

use App\Filament\Resources\WholesalePriceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWholesalePrice extends EditRecord
{
    protected static string $resource = WholesalePriceResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $variant = \App\Models\ProductVariant::find($data['product_variant_id']);
        if ($variant) {
            $data['product_id'] = $variant->product_id;
        }
        return $data;
    }

    protected function mutateFormDataBeforeUpdate(array $data): array
    {
        unset($data['product_id']);
        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
