<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    protected $guarded = [];
    protected $with = ['channel', 'creator'];
    protected $appends = ['isSubscribedTo'];

    use RecordsActivity;

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($thread) {
            $thread->replies->each->delete();
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
        return $this->hasMany(Reply::class)->latest();
    }

    /**
     * A thread can have subscriptions
     *
     * @return void
     */
    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    /**
     * A thread can notify its subscribers about a new reply
     *
     * @return void
     */
    public function notifySubscribers($reply)
    {
        $this->subscriptions
            ->where('user_id', '!=', $reply->user_id)
            ->each
            ->notify($reply);
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
        $reply = $this->replies()->create($reply);

        $this->notifySubscribers($reply);

        return $reply;
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

    /**
     * A thread can be subscribed to.
     *
     * @param int $userId
     * @return App\thread $this
     */
    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id()
        ]);

        return $this;
    }

    /**
     * A thread can be unsubscribed from.
     *
     * @param int $userId
     * @return void
     */
    public function unsubscribe($userId = null)
    {
        $this->subscriptions()->where([
            'user_id' => $userId ?: auth()->id()
        ])->delete();
    }

    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()->where('user_id', auth()->id())->exists();
    }
}
