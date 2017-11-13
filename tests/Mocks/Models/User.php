<?php

namespace SGP\QueryFilters\Tests\Mocks\Models;

use Illuminate\Database\Eloquent\Model;
use SGP\QueryFilters\Filterable;

class User extends Model
{
    use Filterable;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany(Post::class, 'author_id');
    }
}
