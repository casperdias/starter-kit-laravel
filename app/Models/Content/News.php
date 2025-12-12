<?php

namespace App\Models\Content;

use App\Events\Content\News\NewsCreated;
use App\Models\Auth\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $title
 * @property string $type
 * @property string $content
 * @property int $user_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class News extends Model
{
    use HasUuids;

    protected $fillable = [
        'title',
        'type',
        'content',
        'user_id',
    ];

    protected $dispatchesEvents = [
        'created' => NewsCreated::class,
    ];

    /**
     * @param  Builder<News>  $query
     * @return Builder<News>
     */
    public function scopeSearchTitle(Builder $query, string $search): Builder
    {
        if (! $search) {
            return $query;
        }

        return $query->where('title', 'like', "%{$search}%");
    }

    /**
     * @param  Builder<News>  $query
     * @return Builder<News>
     */
    public function scopeFilterCategory(Builder $query, string $category): Builder
    {
        if (! $category || $category === 'all') {
            return $query;
        }

        return $query->where('type', $category);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
