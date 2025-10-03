<?php

use App\Models\Auth\User;
use App\Models\Content\Conversation;
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
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['private', 'group'])->default('private');
            $table->string('name')->nullable(); // For group chats
            $table->text('description')->nullable(); // For group chats
            $table->string('avatar')->nullable(); // For group chats
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        Schema::create('conversation_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Conversation::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->enum('role', ['admin', 'member'])->default('member'); // For group admin
            $table->timestamps();

            $table->unique(['conversation_id', 'user_id']);
        });

        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Conversation::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->text('message');
            $table->string('type')->default('text'); // text, image, file, etc.
            $table->json('metadata')->nullable(); // for file paths, etc.
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
        Schema::dropIfExists('conversation_participants');
        Schema::dropIfExists('conversations');
    }
};
