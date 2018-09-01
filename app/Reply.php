<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class Reply extends Model
{

    public function owner() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
