<?php

namespace App\Filters;

use Illuminate\Http\Request;

class Filter
{
    protected $request;
    protected $builder;

    /**
     * Filter constructor
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

        if ($this->request->has('by')) {
            return $this->by($this->request->by);
        }
    }
}
