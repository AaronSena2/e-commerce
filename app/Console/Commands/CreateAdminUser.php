<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    protected $signature = 'app:create-admin
                            {--name= : Admin user full name}
                            {--email= : Admin user email address}
                            {--password= : Admin user password (prompted if omitted)}';

    protected $description = 'Create a platform admin user and assign the admin role';

    public function handle(): int
    {
        $name = $this->option('name') ?? $this->ask('Admin name');
        $email = $this->option('email') ?? $this->ask('Admin email');
        $password = $this->option('password') ?? $this->secret('Admin password');

        if (User::where('email', $email)->exists()) {
            $this->error("A user with email [{$email}] already exists.");
            return self::FAILURE;
        }

        $user = User::create([
            'name'     => $name,
            'email'    => $email,
            'password' => Hash::make($password),
        ]);

        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            ['description' => 'Platform administrator with full access']
        );

        $user->roles()->attach($adminRole);

        $this->info("Admin user [{$email}] created successfully.");

        return self::SUCCESS;
    }
}
