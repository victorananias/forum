<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class Reply extends Model
{
    protected $guarded = [];
    protected $with = ['owner', 'favorites'];
    protected $withCount = ['favorites'];

    use Favoritable, RecordsActivity;

    /**
     * A reply belongs to an owner.
     *
     * @return void
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
