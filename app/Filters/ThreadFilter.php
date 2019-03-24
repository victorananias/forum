<?php

namespace App\Filters;

use App\User;

class ThreadFilter extends Filter
{
    protected $filters = ['by', 'popular', 'unanswered'];

    /**
     * Filter the query by the given username
     *
     * @param string $username
     * @return mixed
     */
    public function by($username)
    {
        $user = User::whereUsername($username)->firstOrFail();
        return $this->builder->where('user_id', $user->id);
    }

    /**
     * Filter the query by the most popular threads
     *
     * @return mixed
     */
    public function popular()
    {
        return $this->builder->orderBy('replies_count', 'DESC');
    }
    
    /**
     * Filter the query by the less popular threads
     *
     * @return mixed
     */
    public function unanswered()
    {
        return $this->builder->where('replies_count', 0);
    }
}
