<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Carbon;

class User extends Authenticatable implements MustVerifyEmail
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

    /**
     * @param $avatar
     * @return string
     */
    public function getAvatarPathAttribute($avatar)
    {
        return asset('/storage/'.($avatar ?? 'avatars/default.png'));
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return !!$this->is_admin;
    }
}
