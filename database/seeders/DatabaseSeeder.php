<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            SettingsSeeder::class,
            DemoContentSeeder::class,
            EngagementSeeder::class,
        ]);

        // Default super admin (change the password after first login).
        $admin = User::firstOrCreate(
            ['email' => 'admin@quantum.test'],
            ['name' => 'Super Admin', 'password' => Hash::make('password')]
        );

        if (! $admin->hasRole('super_admin')) {
            $admin->assignRole('super_admin');
        }
    }
}
