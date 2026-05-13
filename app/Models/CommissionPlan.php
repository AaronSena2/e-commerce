<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CommissionPlan extends Model
{
    protected $fillable = ['name', 'rate_min_bps', 'rate_max_bps', 'rules_json', 'is_active'];

    protected $casts = [
        'rules_json' => 'array',
        'is_active' => 'boolean',
    ];

    public function vendors(): HasMany
    {
        return $this->hasMany(Vendor::class);
    }
}
