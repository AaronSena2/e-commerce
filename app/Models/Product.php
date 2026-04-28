<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'vendor_id', 'category_id', 'title', 'slug', 'description',
        'status', 'reviewed_by', 'reviewed_at', 'rejection_reason',
    ];

    protected $casts = ['reviewed_at' => 'datetime'];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function skus(): HasMany
    {
        return $this->hasMany(Sku::class);
    }

    public function listingReviews(): HasMany
    {
        return $this->hasMany(ListingReview::class);
    }
}
