<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Currency;
use Illuminate\Database\Seeder;

class CountryAndCurrencySeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            ['name' => 'Uganda',   'iso2' => 'UG', 'iso3' => 'UGA'],
            ['name' => 'Kenya',    'iso2' => 'KE', 'iso3' => 'KEN'],
            ['name' => 'Tanzania', 'iso2' => 'TZ', 'iso3' => 'TZA'],
        ];

        foreach ($countries as $country) {
            Country::firstOrCreate(['iso2' => $country['iso2']], $country);
        }

        $currencies = [
            ['code' => 'UGX', 'name' => 'Ugandan Shilling',  'symbol' => 'USh', 'minor_unit' => 0],
            ['code' => 'KES', 'name' => 'Kenyan Shilling',    'symbol' => 'KSh', 'minor_unit' => 2],
            ['code' => 'TZS', 'name' => 'Tanzanian Shilling', 'symbol' => 'TSh', 'minor_unit' => 2],
        ];

        foreach ($currencies as $currency) {
            Currency::firstOrCreate(['code' => $currency['code']], $currency);
        }
    }
}
