<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    protected $fillable = ['name', 'iso2', 'iso3', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function regions(): HasMany
    {
        return $this->hasMany(Region::class);
    }

    public function taxRules(): HasMany
    {
        return $this->hasMany(TaxRule::class);
    }
}
