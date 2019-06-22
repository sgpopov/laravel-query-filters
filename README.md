# Laravel Query Filters

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://travis-ci.org/sgpopov/laravel-query-filters.svg?branch=master)](https://travis-ci.org/sgpopov/laravel-query-filters)

## About 

Imagine if we have to filter all the users stored in our database by some criteria - 
name, email, location, company, etc. For example:

```http
/users?country=Bulgaria&city=Sofia&name=Svilen
```

To filter by all those parameters we would need to do something like:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = (new User)->newQuery();

        if ($request->has('country')) {
            $users->where('country', '=', $request->get('country'));
        }

        if ($request->has('city')) {
            $users->where('city', '=', $request->get('city'));
        }
        
        if ($request->has('name')) {
            $users->where('name', 'LIKE', '%' . $request->get('name') . '%');
        }

        // ...
        // other filters
        // ...

        return $users->get();
    }
}
```

By using this package you can easily create filters based on the requested query
string and refactor the controller to something like:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;

class UserController extends Controller
{
    public function index(UserFilters $filters)
    {
        return User::filter($filters)->get();
    }
}
```

## Usage

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
        return User::filter($filters)->get();
    }
}
```
