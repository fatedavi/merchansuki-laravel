<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariantWholesalePrice extends Model
{
    protected $fillable = [
        'product_variant_id',
        'min_quantity',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}
