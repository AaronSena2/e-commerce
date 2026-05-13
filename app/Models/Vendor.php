<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vendor extends Model
{
    protected $fillable = [
        'user_id', 'business_name', 'status',
        'commission_plan_id', 'payout_account_ref', 'currency_code',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function commissionPlan(): BelongsTo
    {
        return $this->belongsTo(CommissionPlan::class);
    }

    public function kycDocuments(): HasMany
    {
        return $this->hasMany(VendorKycDocument::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function settlementBatches(): HasMany
    {
        return $this->hasMany(SettlementBatch::class);
    }
}
