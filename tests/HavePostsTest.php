<?php

namespace Tests;

use Tests\Models\Author;
use Rockbuzz\LaraPosts\Models\Post;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HavePostsTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();

        $this->withFactories(__DIR__ . '/database/factories');
    }

    public function testAAuthorCanHavePosts()
    {
        $author = factory(Author::class)->create();

        factory(Post::class, 2)->create(['author_id' => $author->id]);

        $this->assertInstanceOf(HasMany::class, $author->posts());
    }
}
