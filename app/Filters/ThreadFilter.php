<?php

namespace App\Filters;

use Illuminate\Http\Request;
use App\User;

class ThreadFilter
{
    protected $request;
    protected $builder;

    /**
     * ThreadFilter constructor
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply($builder)
    {
        $this->builder = $builder;

        if (!$username = $this->request->by) {
            return $builder;
        }

        if ($username = $this->request->by) {
            $user = User::whereName($username)->firstOrFail();
            return $this->builder->where('user_id', $user->id);
        }
    }
}
