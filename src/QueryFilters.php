<?php

namespace SGP\QueryFilters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class QueryFilters
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var
     */
    protected $builder;

    /**
     * QueryFilters constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Get all request filters data.
     *
     * @return array
     */
    public function filters(): array
    {
        return $this->request->all();
    }

    /**
     * Apply the filters to the builder.
     *
     * @param Builder $builder
     *
     * @return Builder
     */
    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        $filters = $this->filters();

        foreach ($filters as $filter => $value) {
            $filter = Str::studly(Str::lower($filter));

            if ($filter === 'Order') {
                $orderBy = Str::studly(Str::lower($value));
                $method = "orderBy{$orderBy}";
            } else {
                $method = "filterBy{$filter}";
            }

            if (!method_exists($this, $method)) {
                continue;
            }

            if (strlen($value)) {
                $this->$method($value);
            } else {
                $this->$method();
            }
        }

        return $this->builder;
    }

    /**
     * @return string
     */
    public function getSort()
    {
        $sort = 'asc';

        if ($this->request->has('sort')) {
            $sort = Str::lower($this->request->get('sort')) === 'desc' ? 'desc' : 'asc';
        }

        return $sort;
    }
}
