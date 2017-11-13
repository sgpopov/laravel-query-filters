<?php

use Illuminate\Http\Request;
use SGP\QueryFilters\Tests\Mocks\Filters\PostFilters;
use SGP\QueryFilters\Tests\Mocks\Models\Post;
use Orchestra\Testbench\TestCase;

class QueryFiltersTest extends TestCase
{
    /**
     * @test
     */
    public function should_filter_by_title()
    {
        $queryFilters = new Request;
        $queryFilters->merge([
            'title' => 'foo'
        ]);

        $post = Post::filter(
            new PostFilters($queryFilters)
        );

        $expected = [
            [
                'type' => 'Basic',
                'column' => 'title',
                'operator' => 'like',
                'value' => '%foo%',
                'boolean' => 'and'
            ]
        ];

        $this->assertSame($expected, $post->getQuery()->wheres);
    }

    /**
     * @test
     */
    public function should_filter_by_title_and_category()
    {
        $queryFilters = new Request;
        $queryFilters->merge([
            'title' => 'foo',
            'category' => 'bar',
            'created_at' => 'some date'
        ]);

        $post = Post::filter(
            new PostFilters($queryFilters)
        );

        $expected = [
            [
                'type' => 'Basic',
                'column' => 'title',
                'operator' => 'like',
                'value' => '%foo%',
                'boolean' => 'and'
            ],
            [
                'type' => 'Basic',
                'column' => 'category',
                'operator' => '=',
                'value' => 'bar',
                'boolean' => 'and'
            ]
        ];

        $this->assertSame($expected, $post->getQuery()->wheres);
    }

    /**
     * @test
     */
    public function should_not_apply_filter_if_it_has_no_value()
    {
        $queryFilters = new Request;
        $queryFilters->merge([
            'author' => ''
        ]);

        $post = Post::filter(
            new PostFilters($queryFilters)
        );

        $this->assertEmpty($post->getQuery()->wheres);
    }

    /**
     * @test
     */
    public function should_filter_by_related_model_column()
    {
        $queryFilters = new Request;
        $queryFilters->merge([
            'author' => 123
        ]);

        $post = Post::filter(
            new PostFilters($queryFilters)
        );

        $expectedSqlQuery = 'select * from `posts` where exists (select * from `users` where `posts`.`author_id` = `users`.`id` and `id` = ?)';
        $expectedBindings = [
            123
        ];

        $this->assertSame($expectedSqlQuery, $post->toSql());
        $this->assertSame($expectedBindings, $post->getBindings());
    }

    /**
     * @test
     */
    public function should_apply_default_filter_value()
    {
        $queryFilters = new Request;
        $queryFilters->merge([
            'archived' => ''
        ]);

        $post = Post::filter(
            new PostFilters($queryFilters)
        );

        $expected = [
            [
                'type' => 'Basic',
                'column' => 'is_archived',
                'operator' => '=',
                'value' => true,
                'boolean' => 'and',
            ],
        ];

        $this->assertSame($expected, $post->getQuery()->wheres);
    }

    /**
     * @test
     */
    public function should_order_results_by_title()
    {
        $queryFilters = new Request;
        $queryFilters->merge([
            'order' => 'title'
        ]);

        $post = Post::filter(
            new PostFilters($queryFilters)
        );

        $expected = [
            [
                'column' => 'title',
                'direction' => 'asc'
            ],
        ];

        $this->assertSame($expected, $post->getQuery()->orders);
    }

    /**
     * @test
     */
    public function should_order_results_by_title_in_descending_order()
    {
        $queryFilters = new Request;
        $queryFilters->merge([
            'order' => 'title',
            'sort' => 'desc',
        ]);

        $post = Post::filter(
            new PostFilters($queryFilters)
        );

        $expected = [
            [
                'column' => 'title',
                'direction' => 'desc'
            ],
        ];

        $this->assertSame($expected, $post->getQuery()->orders);
    }
}
