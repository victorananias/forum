<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class Thread extends Model
{
    protected $guarded = [];
    protected $with = ['channel', 'creator'];

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('repliesCount', function ($builder) {
            $builder->withCount('replies');
        });

        static::deleting(function ($thread) {
            $thread->replies()->delete();
        });
    }

    /**
     * A thread belongs to a creator.
     *
     * @return void
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * A thread belongs to a channel.
     *
     * @return void
     */
    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    /**
     * A thread has replies.
     *
     * @return void
     */
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    /**
     * Return the uri of the current thread.
     *
     * @return void
     */
    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->id}";
    }

    /**
     * Creates a reply to the current thread.
     *
     * @param array $reply
     * @return void
     */
    public function addReply($reply)
    {
        $this->replies()->create($reply);
    }

    /**
     * Applies filters to the thread's query.
     *
     * @param Illuminate\Database\Query\Builder  $query
     * @param App\Filters\ThreadFilter $filters
     * @return void
     */
    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }
}
