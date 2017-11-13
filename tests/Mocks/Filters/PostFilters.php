<?php

namespace SGP\QueryFilters\Tests\Mocks\Filters;

use SGP\QueryFilters\QueryFilters;

class PostFilters extends QueryFilters
{
    public function filterByTitle($title)
    {
        return $this->builder->where('title', 'like', "%$title%");
    }

    public function filterByCategory($category)
    {
        return $this->builder->where('category', $category);
    }

    public function filterByAuthor($author = null)
    {
        if (empty($author)) {
            return $this->builder;
        }

        return $this->builder->whereHas('author', function ($query) use ($author) {
            return $query->where('id', $author);
        });
    }

    public function filterByArchived($archived = true)
    {
        return $this->builder->where('is_archived', $archived);
    }

    public function orderByTitle()
    {
        $sort = $this->getSort();

        return $this->builder->orderBy('title', $sort);
    }
}
