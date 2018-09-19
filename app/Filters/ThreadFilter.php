<?php

namespace App\Filters;

use App\User;

class ThreadFilter extends Filter
{
    public function by($username)
    {
        $user = User::whereName($username)->firstOrFail();
        return $this->builder->where('user_id', $user->id);
    }
}
