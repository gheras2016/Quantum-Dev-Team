<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Roles/permissions, settings and the super admin are seeded FIRST so
        // the admin account always exists — even if the (non-critical) demo
        // content seeding fails for any reason.
        $this->call([
            RolesAndPermissionsSeeder::class,
            SettingsSeeder::class,
        ]);

        $this->seedSuperAdmin();

        // Demo content is optional; never let a failure here block admin/login.
        try {
            $this->call([
                DemoContentSeeder::class,
                EngagementSeeder::class,
                InboxSeeder::class,
            ]);
        } catch (\Throwable $e) {
            $this->command?->warn('Demo content seeding skipped: '.$e->getMessage());
        }
    }

    /**
     * Create (or update) the super admin. Credentials can be overridden with
     * environment variables (recommended for a public demo so the password is
     * not the repository default). If ADMIN_PASSWORD is provided it is
     * (re)applied on every seed, allowing a password rotation via a redeploy.
     */
    private function seedSuperAdmin(): void
    {
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
