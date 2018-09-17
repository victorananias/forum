<?php

namespace App\Filters;

use Illuminate\Http\Request;

class ThreadFilter
{
    protected $request;

    /**
     * ThreadFilter constructor
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
}
