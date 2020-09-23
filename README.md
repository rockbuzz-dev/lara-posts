# Lara Posts

Management of posts

travis

## Requirements

PHP: >=7.2

## Install

```bash
$ composer require rockbuzz/lara-posts
```

## Configuration

```bash
$ php artisan vendor:publish --provider="Rockbuzz\LaraPosts\ServiceProvider"
$ php artisan migrate
```

## Usage

```php
$post = \Rockbuzz\LaraPosts\Post::find(1);
$post->slug: string
$post->author(): BelongsTo //\App\User::class
$post->isDraft(): bool
$post->isModerate(): bool
$post->isPublished(): bool
$post->isArticle(): bool
$post->isPodcast(): bool
$post->isVideo(): bool
//scopes
$post->drafts(): Builder
$post->moderate(): Builder
$post->published(): Builder
$post->articles(): Builder
$post->podcasts(): Builder
$post->videos(): Builder
$post->latestPublished(): Builder
```

## License

The Lara Posts is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).