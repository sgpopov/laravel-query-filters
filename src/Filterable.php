<?php

namespace SGP\QueryFilters;

use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    /**
     * Filter the result set.
     *
     * @param Builder $query
     * @param QueryFilters $filters
     *
     * @return Builder
     */
    public function scopeFilter(Builder $query, QueryFilters $filters): Builder
    {
        return $filters->apply($query);
    }
}
