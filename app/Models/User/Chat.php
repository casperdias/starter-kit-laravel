<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasUuids;

    protected $fillable = ['user_id', 'message', 'tagged_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function taggedUser()
    {
        return $this->belongsTo(User::class, 'tagged_id');
    }
}
