<?php

namespace App\Filters;

use App\User;

class ThreadFilter extends Filter
{
    protected $filters = ['by'];

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
}
