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
use Illuminate\Database\Eloquent\Model;
use Rockbuzz\LaraPosts\Traits\HavePosts;

class User extends Model
{
    use HavePosts;
    //
}

$author = User::find(1);
$author->posts(): HasMany
```

```php
use Rockbuzz\LaraPosts\Models\Post;

$post = Post::find('uuid');
$post->author(): BelongsTo
$post->isDraft(): bool
$post->isModerate(): bool
$post->isPublished(): bool
$post->isArticle(): bool
$post->isPodcast(): bool
$post->isVideo(): bool
```
Scope
```php
Post::draft(): Builder
Post::moderate(): Builder
Post::published(): Builder
Post::articles(): Builder
Post::podcasts(): Builder
Post::videos(): Builder
Post::latestPublished(): Builder
```

## Use Your Model

```php

namespace App;

use Rockbuzz\LaraPosts\Models\Post;

class YourPost extends Post
{
    //
}

//set in the configuration file
'models' => [
    'post' => \App\YourPost::class,
    //
]
```

## License

The Lara Posts is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).