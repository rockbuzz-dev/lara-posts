<?php

namespace Tests;

use Tests\Stubs\Author;
use Rockbuzz\LaraPosts\Models\Post;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HasPostsTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();

        $this->withFactories(__DIR__ . '/database/factories');
    }

    public function testAAuthorHasPosts()
    {
        $author = factory(Author::class)->create();

        factory(Post::class, 2)->create(['author_id' => $author->id]);

        $this->assertInstanceOf(HasMany::class, $author->posts());
    }
}
