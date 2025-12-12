<?php

namespace Database\Seeders;

use App\Actions\Admin\AssignPermission;
use App\Actions\Admin\AssignRole;
use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(
        AssignPermission $assignPermission,
        AssignRole $assignRole,
    ): void {
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
            ['name' => 'view-repo', 'display_name' => 'View Repository', 'description' => 'Permission to view the repository'],
            ['name' => 'create-news', 'display_name' => 'Create News', 'description' => 'Permission to create news articles'],
        ];

        foreach ($permissions as $perm) {
            $permission = Permission::create($perm);
            $assignPermission->handle($adminRole, $permission);
        }

        // Assign role to user
        $assignRole->handle($admin, $adminRole);
    }
}
