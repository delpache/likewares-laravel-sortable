<?php

namespace Likewares\Sortable\Tests;

use Likewares\Sortable\Provider;
use Likewares\Sortable\Tests\Models\Comment;
use Likewares\Sortable\Tests\Models\Post;
use Likewares\Sortable\Tests\Models\Profile;
use Likewares\Sortable\Tests\Models\User;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected $user;

    protected $profile;

    protected $post;

    protected $comment;

    protected $direction = 'asc';

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();

        $this->setUpModels();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    protected function getPackageProviders($app)
    {
        return [
            Provider::class,
        ];
    }

    protected function setUpDatabase()
    {
        config(['database.default' => 'testbench']);

        config(['database.connections.testbench' => [
                'driver'   => 'sqlite',
                'database' => ':memory:',
                'prefix'   => '',
            ],
        ]);
    }

    protected function setUpModels()
    {
        $this->user    = new User();
        $this->profile = new Profile();
        $this->post    = new Post();
        $this->comment = new Comment();
    }

    public function getNextClosure()
    {
        return function () {
            return 'next';
        };
    }
}
