<?php

namespace App\Models\Content;

use App\Models\Auth\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property-read string $id
 * @property int $conversation_id
 * @property int $user_id
 * @property string $message
 * @property string $type
 * @property string $metadata
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Chat extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = ['conversation_id', 'user_id', 'message', 'type', 'metadata'];

    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * @return BelongsTo<Conversation, $this>
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
