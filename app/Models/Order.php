<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'customer_id', 'country_id', 'order_number', 'status',
        'payment_method', 'currency_code',
        'subtotal_minor', 'shipping_total_minor', 'tax_total_minor',
        'discount_total_minor', 'total_minor',
        'shipping_name', 'shipping_address_line1', 'shipping_address_line2', 'shipping_city',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function codRequest(): HasOne
    {
        return $this->hasOne(CodRequest::class);
    }

    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class);
    }

    public function escrowHold(): HasOne
    {
        return $this->hasOne(EscrowHold::class);
    }
}
