# Lara Posts

Management of posts

[![Build Status](https://travis-ci.org/rockbuzz/lara-posts.svg?branch=master)](https://travis-ci.org/rockbuzz/lara-posts)

## Requirements

PHP >=7.2

## Install

```bash
$ composer require rockbuzz/lara-posts
```

```bash
$ php artisan vendor:publish --provider="Rockbuzz\LaraPosts\ServiceProvider" --tag="migrations"
```

```bash
$ php artisan migrate
```

## Config

```bash
$ php artisan vendor:publish --provider="Rockbuzz\LaraPosts\ServiceProvider" --tag="config"
```

```php
<?php

return [
    'tables' => [
        'authors' => 'users'
    ],
    'route_key_name' => 'slug',
    'models' => [
        'post' => \Rockbuzz\LaraPosts\Models\Post::class,
        'author' => \App\User::class,
    ]
];
```

## Usage

```php
$post = \Rockbuzz\LaraPosts\Post::find(1);
$post->author(): BelongsTo
$post->isDraft(): bool
$post->isModerate(): bool
$post->isPublished(): bool
$post->isArticle(): bool
$post->isPodcast(): bool
$post->isVideo(): bool
//scopes
$post->draft(): Builder
$post->moderate(): Builder
$post->published(): Builder
$post->articles(): Builder
$post->podcasts(): Builder
$post->videos(): Builder
$post->latestPublished(): Builder
```

## License

The Lara Posts is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).