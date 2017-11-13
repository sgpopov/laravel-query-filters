# Laravel Query Filters

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://travis-ci.org/svil4ok/laravel-query-filters.svg?branch=master)](https://travis-ci.org/svil4ok/laravel-query-filters)

### Usage

* Add `Filterable` trait to your model to allow the usage of `Model::filter()`.

```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use SGP\QueryFilters\Filterable;

class User extends Model
{
    use Filterable;
}
```

* Generate your model filters using the following boilerplate:

```php
<?php

namespace App;

use SGP\QueryFilters\QueryFilters;

class UserFilters extends QueryFilters
{
    public function filterByOption($value)
    {
        return $this->builder->where('column', 'operator', $value);
    }
}
```

* Use your filter:

```php
<?php

namespace App\Http\Controllers;

use App\User;
use App\UserFilters;

class MyController extends Controller
{
    public function index(UserFilters $filters)
    {
        $user = User::filter($filters)->get();

        return response()->json($user);
    }
}
```
