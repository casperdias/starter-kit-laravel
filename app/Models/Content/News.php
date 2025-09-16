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

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
