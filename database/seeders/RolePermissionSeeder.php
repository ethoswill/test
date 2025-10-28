<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            // Page view permissions - mapped to navigation items
            ['name' => 'View CAD Library', 'slug' => 'cad-library.view', 'resource' => 'cad-library', 'action' => 'view'],
            ['name' => 'View Direct To Film', 'slug' => 'direct-to-film.view', 'resource' => 'direct-to-film', 'action' => 'view'],
            ['name' => 'View Puff Print', 'slug' => 'puff-print.view', 'resource' => 'puff-print', 'action' => 'view'],
            ['name' => 'View Bottles', 'slug' => 'bottles.view', 'resource' => 'bottles', 'action' => 'view'],
            ['name' => 'View Styles', 'slug' => 'styles.view', 'resource' => 'styles', 'action' => 'view'],
            ['name' => 'View Thread Book Colors', 'slug' => 'thread-colors.view', 'resource' => 'thread-colors', 'action' => 'view'],
            ['name' => 'View Grips', 'slug' => 'grips.view', 'resource' => 'grips', 'action' => 'view'],
            ['name' => 'View Packaging', 'slug' => 'packaging.view', 'resource' => 'packaging', 'action' => 'view'],
            ['name' => 'View Team Members', 'slug' => 'team-members.view', 'resource' => 'team-members', 'action' => 'view'],
            
            // Product permissions
            ['name' => 'View Products', 'slug' => 'products.view', 'resource' => 'products', 'action' => 'view'],
            ['name' => 'Create Products', 'slug' => 'products.create', 'resource' => 'products', 'action' => 'create'],
            ['name' => 'Update Products', 'slug' => 'products.update', 'resource' => 'products', 'action' => 'update'],
            ['name' => 'Delete Products', 'slug' => 'products.delete', 'resource' => 'products', 'action' => 'delete'],
            ['name' => 'Import Products', 'slug' => 'products.import', 'resource' => 'products', 'action' => 'import'],
            
            // User permissions
            ['name' => 'View Users', 'slug' => 'users.view', 'resource' => 'users', 'action' => 'view'],
            ['name' => 'Create Users', 'slug' => 'users.create', 'resource' => 'users', 'action' => 'create'],
            ['name' => 'Update Users', 'slug' => 'users.update', 'resource' => 'users', 'action' => 'update'],
            ['name' => 'Delete Users', 'slug' => 'users.delete', 'resource' => 'users', 'action' => 'delete'],
            
            // Role permissions
            ['name' => 'View Roles', 'slug' => 'roles.view', 'resource' => 'roles', 'action' => 'view'],
            ['name' => 'Create Roles', 'slug' => 'roles.create', 'resource' => 'roles', 'action' => 'create'],
            ['name' => 'Update Roles', 'slug' => 'roles.update', 'resource' => 'roles', 'action' => 'update'],
            ['name' => 'Delete Roles', 'slug' => 'roles.delete', 'resource' => 'roles', 'action' => 'delete'],
            
            // Permission permissions
            ['name' => 'View Permissions', 'slug' => 'permissions.view', 'resource' => 'permissions', 'action' => 'view'],
            ['name' => 'Create Permissions', 'slug' => 'permissions.create', 'resource' => 'permissions', 'action' => 'create'],
            ['name' => 'Update Permissions', 'slug' => 'permissions.update', 'resource' => 'permissions', 'action' => 'update'],
            ['name' => 'Delete Permissions', 'slug' => 'permissions.delete', 'resource' => 'permissions', 'action' => 'delete'],
            
            // System permissions
            ['name' => 'Access Admin Panel', 'slug' => 'admin.access', 'resource' => 'admin', 'action' => 'access'],
            ['name' => 'Manage Settings', 'slug' => 'settings.manage', 'resource' => 'settings', 'action' => 'manage'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }

        // Create roles
        $roles = [
            [
                'name' => 'Super Admin',
                'slug' => 'super-admin',
                'description' => 'Full access to all features and settings',
                'permissions' => Permission::all()->pluck('slug')->toArray(),
            ],
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Administrative access to products and users',
                'permissions' => [
                    'cad-library.view',
                    'direct-to-film.view',
                    'puff-print.view',
                    'bottles.view',
                    'styles.view',
                    'thread-colors.view',
                    'grips.view',
                    'packaging.view',
                    'team-members.view',
                    'products.view', 'products.create', 'products.update', 'products.delete', 'products.import',
                    'users.view', 'users.create', 'users.update',
                    'roles.view',
                    'admin.access',
                ],
            ],
            [
                'name' => 'Product Manager',
                'slug' => 'product-manager',
                'description' => 'Manage products and import data',
                'permissions' => [
                    'cad-library.view',
                    'direct-to-film.view',
                    'puff-print.view',
                    'bottles.view',
                    'styles.view',
                    'thread-colors.view',
                    'grips.view',
                    'packaging.view',
                    'products.view', 'products.create', 'products.update', 'products.import',
                    'admin.access',
                ],
            ],
            [
                'name' => 'Viewer',
                'slug' => 'viewer',
                'description' => 'View-only access to products',
                'permissions' => [
                    'cad-library.view',
                    'direct-to-film.view',
                    'puff-print.view',
                    'bottles.view',
                    'styles.view',
                    'thread-colors.view',
                    'grips.view',
                    'packaging.view',
                    'products.view',
                    'admin.access',
                ],
            ],
        ];

        foreach ($roles as $roleData) {
            $permissions = $roleData['permissions'];
            unset($roleData['permissions']);
            
            $role = Role::firstOrCreate(
                ['slug' => $roleData['slug']],
                $roleData
            );
            
            // Assign permissions to role
            $permissionIds = Permission::whereIn('slug', $permissions)->pluck('id');
            $role->permissions()->sync($permissionIds);
        }

        // Assign Super Admin role to existing admin user
        $adminUser = User::where('email', 'admin@example.com')->first();
        if ($adminUser) {
            $superAdminRole = Role::where('slug', 'super-admin')->first();
            if ($superAdminRole) {
                $adminUser->assignRole($superAdminRole);
            }
        }
    }
}