<?php

namespace Database\Seeders;

use App\Models\User\Permission;
use App\Models\User\Role;
use App\Models\User\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        // Verify the user
        $admin->markEmailAsVerified();

        // Make Role Admin
        $adminRole = Role::create([
            'name' => 'Admin',
            'display_name' => 'Administrator',
            'description' => 'User with full access to the system',
        ]);

        $permissions = [
            ['name' => 'user', 'display_name' => 'User', 'description' => 'Permission to manage users'],
            ['name' => 'role', 'display_name' => 'Role', 'description' => 'Permission to manage roles'],
            ['name' => 'permission', 'display_name' => 'Permission', 'description' => 'Permission to manage permissions'],
        ];

        foreach ($permissions as $perm) {
            $permission = Permission::create($perm);
            $adminRole->assignPermission($permission);
        }

        // Assign role to user
        $admin->assignRole($adminRole);
    }
}
