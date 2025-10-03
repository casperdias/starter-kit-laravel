<?php

namespace App\Models\Content;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['type', 'name', 'description', 'avatar', 'created_by'];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function participants()
    {
        return $this->belongsToMany(User::class, 'conversation_participants')
            ->withPivot(['role', 'last_read_at'])
            ->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(Chat::class)->latest();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Helper method to find or create private conversation
    public static function findOrCreatePrivate($user1, $user2)
    {
        $conversation = self::where('type', 'private')
            ->whereHas('participants', function ($query) use ($user1) {
                $query->where('user_id', $user1);
            })
            ->whereHas('participants', function ($query) use ($user2) {
                $query->where('user_id', $user2);
            })
            ->first();

        if (! $conversation) {
            $conversation = self::create([
                'type' => 'private',
                'created_by' => $user1,
            ]);

            $conversation->participants()->attach([$user1, $user2]);
        }

        return $conversation;
    }

    // Check if user is participant
    public function isParticipant($userId)
    {
        return $this->participants()->where('user_id', $userId)->exists();
    }
}
