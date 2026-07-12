<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Resources that share the standard view/create/edit/delete permission set.
     */
    private array $crudResources = [
        'services', 'projects', 'team', 'technologies', 'categories', 'social_links',
        'posts', 'testimonials', 'faqs',
    ];

    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = $this->buildPermissions();

        foreach ($permissions as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        $roles = [
            'super_admin' => $permissions, // gets everything (also bypasses via Gate::before)
            'admin' => $permissions,
            'content_manager' => $this->contentManagerPermissions(),
            'editor' => $this->editorPermissions(),
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($rolePermissions);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    private function buildPermissions(): array
    {
        $permissions = ['view_dashboard', 'view_profile', 'edit_profile', 'view_settings', 'edit_settings'];

        foreach ($this->crudResources as $resource) {
            foreach (['view', 'create', 'edit', 'delete'] as $ability) {
                $permissions[] = "{$ability}_{$resource}";
            }
        }

        foreach (['contacts', 'project_requests'] as $resource) {
            $permissions[] = "view_{$resource}";
            $permissions[] = "update_{$resource}";
            $permissions[] = "delete_{$resource}";
        }

        // User management — granted to super_admin & admin only (not the curated
        // content_manager / editor sets below).
        foreach (['view', 'create', 'edit', 'delete'] as $ability) {
            $permissions[] = "{$ability}_users";
        }

        return $permissions;
    }

    private function contentManagerPermissions(): array
    {
        $permissions = ['view_dashboard', 'view_profile', 'edit_profile'];

        foreach ($this->crudResources as $resource) {
            $permissions = array_merge($permissions, ["view_{$resource}", "create_{$resource}", "edit_{$resource}"]);
        }

        foreach (['contacts', 'project_requests'] as $resource) {
            $permissions[] = "view_{$resource}";
            $permissions[] = "update_{$resource}";
        }

        return $permissions;
    }

    private function editorPermissions(): array
    {
        $permissions = ['view_dashboard', 'view_profile', 'edit_profile'];

        foreach ($this->crudResources as $resource) {
            $permissions = array_merge($permissions, ["view_{$resource}", "edit_{$resource}"]);
        }

        foreach (['contacts', 'project_requests'] as $resource) {
            $permissions[] = "view_{$resource}";
            $permissions[] = "update_{$resource}";
        }

        return $permissions;
    }
}
