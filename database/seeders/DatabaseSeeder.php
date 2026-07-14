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

        // Default super admin. Credentials can be overridden with environment
        // variables (recommended for a public demo so the password is not the
        // repository default). If ADMIN_PASSWORD is provided it is (re)applied
        // on every seed, allowing a password rotation via a redeploy.
        $email = env('ADMIN_EMAIL', 'admin@quantum.test');

        $admin = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => env('ADMIN_NAME', 'Super Admin'),
                'password' => Hash::make(env('ADMIN_PASSWORD', 'password')),
            ]
        );

        if (env('ADMIN_PASSWORD')) {
            $admin->update(['password' => Hash::make(env('ADMIN_PASSWORD'))]);
        }

        if (! $admin->hasRole('super_admin')) {
            $admin->assignRole('super_admin');
        }
    }
}
