<?php

namespace App\Filters;

use App\User;

class ThreadFilter extends Filter
{
    protected $filters = ['by', 'popular'];

    /**
     * Filter the query by the given username
     *
     * @param string $username
     * @return mixed
     */
    public function by($username)
    {
        $user = User::whereName($username)->firstOrFail();
        return $this->builder->where('user_id', $user->id);
    }

    /**
     * Filter the query by the amount of replies
     *
     * @return mixed
     */
    public function popular()
    {
        return $this->builder->orderBy('replies_count', 'DESC');
    }
}
