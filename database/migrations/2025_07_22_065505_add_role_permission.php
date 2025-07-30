<?php

use App\Models\User\Permission;
use App\Models\User\Role;
use App\Models\User\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add table for roles
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('display_name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Add table for permissions
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('display_name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Add table for role_permission
        Schema::create('role_permission', function (Blueprint $table) {
            $table->foreignIdFor(Permission::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Role::class)->constrained()->cascadeOnDelete();
            $table->primary(['permission_id', 'role_id']);
        });

        // Add table for role_user and status ACTIVE IN ACTIVE
        Schema::create('user_role', function (Blueprint $table) {
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Role::class)->constrained()->cascadeOnDelete();
            $table->primary(['user_id', 'role_id']);
            $table->enum('status', ['ACTIVE', 'INACTIVE'])->default('INACTIVE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the user_role table
        Schema::dropIfExists('user_role');

        // Drop the role_permission table
        Schema::dropIfExists('role_permission');

        // Drop the permissions table
        Schema::dropIfExists('permissions');

        // Drop the roles table
        Schema::dropIfExists('roles');
    }
};
