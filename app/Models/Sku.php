<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sku extends Model
{
    protected $fillable = [
        'product_id', 'sku_code', 'currency_code',
        'price_minor', 'sale_price_minor', 'attributes_json',
        'express_eligible', 'status',
    ];

    protected $casts = [
        'attributes_json' => 'array',
        'express_eligible' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function inventoryBalances(): HasMany
    {
        return $this->hasMany(InventoryBalance::class);
    }
}
