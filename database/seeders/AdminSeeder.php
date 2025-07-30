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

        // Make Permissions
        $permissions = Permission::create([
            'name' => 'admin',
            'display_name' => 'admin',
            'description' => 'Permission to manage all aspects of the application',
        ]);

        // Assign permissions to role
        $adminRole->assignPermission($permissions);

        // Assign role to user
        $admin->assignRole($adminRole);
    }
}
