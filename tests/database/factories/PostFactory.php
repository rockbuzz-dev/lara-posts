<?php

use Tests\Stubs\Author;
use Illuminate\Support\Str;
use Faker\Generator as Faker;
use Rockbuzz\LaraPosts\Models\Post;
use Rockbuzz\LaraPosts\Enums\{Status, Type};

$factory->define(Post::class, function (Faker $faker) {
    $title = $faker->unique()->sentence;
    return [
        'title' => $title,
        'slug' => Str::slug($title),
        'description' => $faker->sentence,
        'body' => $faker->text(500),
        'view' => random_int(0, 100),
        'status' => Status::DRAFT,
        'type' => Type::ARTICLE,
        'metadata' => null,
        'order_column' => null,
        'author_id' => function () {
            return factory(Author::class)->create();
        },
        'published_at' => $faker->dateTimeAD
    ];
});
