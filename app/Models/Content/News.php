<?php

namespace App\Models\Content;

use App\Events\Content\News\NewsCreated;
use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

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

    public function scopeSearchTitle($query, $search)
    {
        if (! $search) {
            return $query;
        }

        return $query->where('title', 'like', "%{$search}%");
    }

    public function scopeFilterCategory($query, $category)
    {
        if (! $category || $category === 'all') {
            return $query;
        }

        return $query->where('type', $category);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
