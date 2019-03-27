<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Carbon;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar_path', 'username'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
             if (preg_match('/[^A-z0-9_-]/', $model->username)) {
                throw new \Exception('The username can not contain special characteres.');
             }
        });
    }

    /**
     * set the route key
     *
     * @return mixed
     */
    public function getRouteKeyName()
    {
        return 'username';
    }

    /**
     * A user can have many threads.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function threads()
    {
        return $this->hasMany(Thread::class)->latest();
    }

    /**
     * A user can have a last reply.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    protected function lastReply()
    {
        return $this->hasOne(Reply::class)->latest();
    }

    /**
     * A user can have many activities.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * Returns the thread cache key
     *
     * @param $thread
     * @return string
     */
    public function visitedThreadCacheKey($thread)
    {
        return sprintf('users.%s.visits.%s', $this->id, $thread->id);
    }

    /**
     * Mark the specified thread as read
     *
     * @param $thread
     * @throws \Exception
     */
    public function read($thread)
    {
        cache()->forever($this->visitedThreadCacheKey($thread), Carbon::now());
    }

    public function avatar()
    {
        return asset('storage/'.($this->avatar_path ?? 'avatars/default.png'));
    }
}
