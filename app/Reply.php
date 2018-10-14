<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class Reply extends Model
{
    protected $guarded = [];
    protected $with = ['owner', 'favorites'];
    protected $withCount = ['favorites'];

    use Favoritable;

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
