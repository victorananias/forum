<?php

namespace App\Filters;

use Illuminate\Http\Request;

class Filter
{
    protected $request;
    protected $builder;
    protected $filters = [];

    /**
     * Filter constructor
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * apply the request filters
     *
     * @param $builder
     * @return void
     */
    public function apply($builder)
    {
        $this->builder = $builder;

        foreach ($this->getFilters() as $filter => $value) {
            if (method_exists($this, $filter)) {
                $this->$filter($value);
            }
        }

        return $this->builder;
    }

    /**
     * return the valid filters on the request
     *
     * @return array
     */
    protected function getFilters()
    {
        return $this->request->only($this->filters);
    }
}
