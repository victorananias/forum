<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Carbon\Carbon;
use Stevebauman\Purify\Purify;

class Reply extends Model
{
    use Favoritable, RecordsActivity;

    protected $with = ['owner', 'favorites'];
    protected $appends = ['htmlBody', 'favoritesCount', 'isFavorited', 'isBest'];
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * A reply belongs to an thread.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function thread()
    {
        return $this->belongsTo(Thread::class, 'thread_id');
    }

    /**
     * @return string|string[]|null
     */
    public function getHtmlBodyAttribute() {
        return preg_replace('/@([\w\-\_]+)/', '<a href="/profiles/$1">$0</a>', $this->body);
    }

    /**
     * Determinate the path to the reply.
     *
     * @return string
     */
    public function path()
    {
        return $this->thread->path() . "#reply-{$this->id}";
    }

    /**
     * Determinate if the reply was just published a moment ago.
     *
     * @return mixed
     */
    public function wasJustPublished()
    {
        return $this->created_at->gt(Carbon::now()->subMinute());
    }

    /**
     * Returns the name of the mentioned users.
     *
     * @return mixed
     */
    public function mentionedUsers()
    {
        preg_match_all('/@([\w\-\_]+)/', $this->body, $matches);

        return $matches[1];
    }

    public function getIsBestAttribute()
    {
        return $this->thread->best_reply_id == $this->id;
    }

    public function getBodyAttribute($body)
    {
        return (new Purify)->clean($body);
    }
}
