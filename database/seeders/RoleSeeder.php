<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'customer',          'description' => 'End consumer browsing and purchasing products'],
            ['name' => 'vendor',            'description' => 'Seller / merchant listing products on the platform'],
            ['name' => 'admin',             'description' => 'Platform administrator with full access'],
            ['name' => 'content_reviewer',  'description' => 'Reviews and approves/rejects product listings'],
            ['name' => 'logistics_partner', 'description' => 'Third-party logistics partner managing shipments'],
            ['name' => 'agent',             'description' => 'JForce offline-to-online sales agent'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name']], $role);
        }
    }
}
