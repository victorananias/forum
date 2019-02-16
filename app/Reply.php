<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class Reply extends Model
{
    use Favoritable, RecordsActivity;

    protected $with = ['owner', 'favorites'];
    protected $appends = ['favoritesCount', 'isFavorited'];
    protected $fillable = ['thread_id', 'user_id', 'body'];

    public static function boot()
    {
        parent::boot();

        static::created(function ($reply) {
            $reply->thread->increment('replies_count');
        });
        
        static::deleting(function ($reply) {
            $reply->thread->decrement('replies_count');
        });
    }

    /**
     * A reply belongs to an owner.
     *
     * @return void
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    /**
     * A reply belongs to an thread.
     *
     * @return void
     */
    public function thread()
    {
        return $this->belongsTo(Thread::class, 'thread_id');
    }
    
    public function path()
    {
        return $this->thread->path(). "#reply-{$this->id}";
    }
}
