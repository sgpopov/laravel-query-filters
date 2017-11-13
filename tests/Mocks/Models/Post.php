<?php

namespace SGP\QueryFilters\Tests\Mocks\Models;

use Illuminate\Database\Eloquent\Model;
use SGP\QueryFilters\Filterable;

class Post extends Model
{
    use Filterable;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
