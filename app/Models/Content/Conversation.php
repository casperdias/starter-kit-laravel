<?php

namespace App\Models\Content;

use App\Models\Auth\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property-read int $id
 * @property string $type
 * @property string $name
 * @property string $description
 * @property string $avatar
 * @property int $created_by
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Conversation extends Model
{
    protected $fillable = ['type', 'name', 'description', 'avatar', 'created_by'];

    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * @return BelongsToMany<User, $this>
     */
    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'conversation_participants')
            ->withPivot(['role', 'joined_at']);
    }

    /**
     * @return HasMany<Chat, $this>
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Chat::class)->latest();
    }

    /**
     * @return HasOne<Chat, $this>
     */
    public function lastMessage(): HasOne
    {
        return $this->hasOne(Chat::class)->latest('created_at');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isParticipant(int $userId): bool
    {
        return $this->participants()->where('user_id', $userId)->exists();
    }
}
