<?php

namespace Tests;

use Tests\Models\Author;
use Spatie\Sluggable\HasSlug;
use Rockbuzz\LaraPosts\Models\Post;
use Illuminate\Support\Facades\Config;
use Spatie\EloquentSortable\SortableTrait;
use Rockbuzz\LaraPosts\Enums\{Status, Type};
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\SchemalessAttributes\SchemalessAttributesTrait;

class PostTest extends TestCase
{
    /**
     * @var Post
     */
    private $post;

    public function setUp(): void
    {
        parent::setUp();

        $this->withFactories(__DIR__ . '/database/factories');

        $this->post = new Post();
    }

    public function testPostFillable()
    {
        $expected = [
            'title',
            'slug',
            'description',
            'body',
            'view',
            'status',
            'type',
            'metadata',
            'order_column',
            'author_id',
            'published_at'
        ];

        $this->assertEquals($expected, $this->post->getFillable());
    }

    public function testIfUsesTraits()
    {
        $this->assertEquals(
            [
                HasSlug::class,
                SortableTrait::class,
                SoftDeletes::class,
                SchemalessAttributesTrait::class
            ],
            array_values(class_uses(Post::class))
        );
    }

    public function testCasts()
    {
        $this->assertEquals([
            'metadata' => 'array',
            'status' => 'int',
            'type' => 'int',
            'id' => 'int'
        ], $this->post->getCasts());
    }

    public function testDates()
    {
        $this->assertEquals(
            array_values([
                'published_at',
                'deleted_at',
                'created_at',
                'updated_at',
            ]),
            array_values($this->post->getDates())
        );
    }

    public function testAPostShouldHaveASlugAttribute()
    {
        $post = factory(Post::class)->create(['title' => 'title test x', 'slug' => null]);

        $this->assertEquals('title-test-x', $post->slug);
    }

    public function testAPostHasAuthor()
    {
        $post = factory(Post::class)->create();

        Config::set('posts.models.author', Author::class);

        $this->assertInstanceOf(BelongsTo::class, $post->author());
    }

    public function testItShouldBeIsDraft()
    {
        $post = factory(Post::class)->create(['status' => Status::DRAFT]);

        $this->assertTrue($post->isDraft());
        $this->assertFalse($post->isModerate());
        $this->assertFalse($post->isPublished());
    }

    public function testItShouldBeIsModerate()
    {
        $post = factory(Post::class)->create(['status' => Status::MODERATE]);

        $this->assertTrue($post->isModerate());
        $this->assertFalse($post->isDraft());
        $this->assertFalse($post->isPublished());
    }

    public function testItShouldBeIsPublished()
    {
        $post = factory(Post::class)->create(['status' => Status::APPROVED]);

        $this->assertTrue($post->isPublished());
        $this->assertFalse($post->isModerate());
        $this->assertFalse($post->isDraft());
    }

    public function testItShouldHaveTwoDraftItems()
    {
        factory(Post::class, 2)->create(['status' => Status::DRAFT]);
        factory(Post::class, 3)->create(['status' => Status::MODERATE]);
        factory(Post::class, 4)->create(['status' => Status::APPROVED]);

        $this->assertEquals(2, Post::draft()->count());
    }

    public function testItShouldHaveTreeModerateItems()
    {
        factory(Post::class, 2)->create(['status' => Status::DRAFT]);
        factory(Post::class, 3)->create(['status' => Status::MODERATE]);
        factory(Post::class, 4)->create(['status' => Status::APPROVED]);

        $this->assertEquals(3, Post::moderate()->count());
    }

    public function testItShouldHaveFourPublishedItems()
    {
        factory(Post::class, 2)->create(['status' => Status::DRAFT]);
        factory(Post::class, 3)->create(['status' => Status::MODERATE]);
        factory(Post::class, 4)->create([
            'status' => Status::APPROVED,
            'published_at' => now()->addMinute()
        ]);
        factory(Post::class, 5)->create([
            'status' => Status::APPROVED,
            'published_at' => now()->subMinute()
        ]);

        $this->assertEquals(5, Post::published()->count());
    }

    public function testItShouldBeIsArticle()
    {
        $post = factory(Post::class)->create(['type' => Type::ARTICLE]);

        $this->assertTrue($post->isArticle());
        $this->assertFalse($post->isPodcast());
        $this->assertFalse($post->isVideo());
    }

    public function testItShouldBeIsPodcast()
    {
        $post = factory(Post::class)->create(['type' => Type::PODCAST]);

        $this->assertTrue($post->isPodcast());
        $this->assertFalse($post->isArticle());
        $this->assertFalse($post->isVideo());
    }

    public function testItShouldBeIsVideo()
    {
        $post = factory(Post::class)->create(['type' => Type::VIDEO]);

        $this->assertTrue($post->isVideo());
        $this->assertFalse($post->isPodcast());
        $this->assertFalse($post->isArticle());
    }

    public function testItShouldHaveTwoItemsArticle()
    {
        factory(Post::class, 2)->create(['type' => Type::ARTICLE]);
        factory(Post::class, 3)->create(['type' => Type::PODCAST]);
        factory(Post::class, 4)->create(['type' => Type::VIDEO]);

        $this->assertEquals(2, Post::article()->count());
    }

    public function testItShouldHaveTreeItemsPodcast()
    {
        factory(Post::class, 2)->create(['type' => Type::ARTICLE]);
        factory(Post::class, 3)->create(['type' => Type::PODCAST]);
        factory(Post::class, 4)->create(['type' => Type::VIDEO]);

        $this->assertEquals(3, Post::podcast()->count());
    }

    public function testItShouldHaveFourItemsVideo()
    {
        factory(Post::class, 2)->create(['type' => Type::ARTICLE]);
        factory(Post::class, 3)->create(['type' => Type::PODCAST]);
        factory(Post::class, 4)->create(['type' => Type::VIDEO]);

        $this->assertEquals(4, Post::video()->count());
    }

    public function testItShouldHaveLatestPublishedItems()
    {
        $post1 = factory(Post::class)->create([
            'status' => Status::APPROVED,
            'published_at' => now()->subHours(1)
        ]);
        $post2 = factory(Post::class)->create([
            'status' => Status::APPROVED,
            'published_at' => now()->subHours(2)
        ]);
        $post3 = factory(Post::class)->create([
            'status' => Status::APPROVED,
            'published_at' => now()->subHours(3)
        ]);
        factory(Post::class, 2)->create([
            'status' => Status::DRAFT,
            'published_at' => now()->subHours(1)
        ]);
        factory(Post::class, 3)->create([
            'status' => Status::MODERATE,
            'published_at' => now()->subHours(2)
        ]);

        $data = [
            $post3->title,
            $post2->title,
            $post1->title,
        ];

        $this->assertEquals($data, Post::latestPublished()->get()->pluck('title')->toArray());
    }

    public function testItShouldHaveLatestScheduledItems()
    {
        $posts = factory(Post::class, 5)->create([
            'status' => Status::APPROVED,
            'published_at' => now()->subDay()
        ]);
        $post = factory(Post::class)->create([
            'status' => Status::APPROVED,
            'published_at' => now()->addDays(3)
        ]);
        factory(Post::class)->create([
            'status' => Status::DRAFT,
            'published_at' => now()->addDays(2)
        ]);
        factory(Post::class)->create([
            'status' => Status::MODERATE,
            'published_at' => now()->addDays(1)
        ]);
        $this->assertTrue(Post::scheduled()->get()->contains($post));
        $posts->each(function ($post) {
            $this->assertFalse(Post::scheduled()->get()->contains($post));
        });
    }
}
