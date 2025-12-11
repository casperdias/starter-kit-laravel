<?php

namespace App\Models\Content;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Conversation extends Model
{
    protected $fillable = ['type', 'name', 'description', 'avatar', 'created_by'];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'conversation_participants')
            ->withPivot(['role', 'joined_at']);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Chat::class)->latest();
    }

    public function lastMessage(): HasOne
    {
        return $this->hasOne(Chat::class)->latest('created_at');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isParticipant($userId): bool
    {
        return $this->participants()->where('user_id', $userId)->exists();
    }
}
