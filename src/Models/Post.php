<?php

namespace Rockbuzz\LaraPosts\Models;

use Rockbuzz\LaraUuid\Traits\Uuid;
use Spatie\Sluggable\{HasSlug, SlugOptions};
use Rockbuzz\LaraPosts\Enums\{Status, Type};
use Spatie\SchemalessAttributes\SchemalessAttributes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\EloquentSortable\{Sortable, SortableTrait};
use Illuminate\Database\Eloquent\{Builder, Model, SoftDeletes};

class Post extends Model implements Sortable
{
    use Uuid, HasSlug, SortableTrait, SoftDeletes;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
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

    protected $casts = [
        'id' => 'string',
        'metadata' => 'array',
        'status' => 'int',
        'type' => 'int'
    ];

    protected $dates = [
        'published_at'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('posts.tables.posts'));
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function getMetadataAttribute(): SchemalessAttributes
    {
        return SchemalessAttributes::createForModel($this, 'metadata');
    }

    public function scopeWithMetadata(): Builder
    {
        return SchemalessAttributes::scopeWithSchemalessAttributes('metadata');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(config('posts.models.author'), 'author_id');
    }

    public function isDraft(): bool
    {
        return $this->status === Status::DRAFT;
    }

    public function isModerate(): bool
    {
        return $this->status === Status::MODERATE;
    }

    public function isPublished(): bool
    {
        return $this->status === Status::PUBLISHED;
    }

    public function isArticle(): bool
    {
        return $this->type === Type::ARTICLE;
    }

    public function isPodcast(): bool
    {
        return $this->type === Type::PODCAST;
    }

    public function isVideo(): bool
    {
        return $this->type === Type::VIDEO;
    }

    public function scopeDrafts($query): Builder
    {
        return $query->whereStatus(Status::DRAFT);
    }

    public function scopeModerate($query): Builder
    {
        return $query->whereStatus(Status::MODERATE);
    }

    public function scopePublished($query): Builder
    {
        return $query->whereStatus(Status::PUBLISHED);
    }

    public function scopeArticles($query): Builder
    {
        return $query->whereType(Type::ARTICLE);
    }

    public function scopePodcasts($query): Builder
    {
        return $query->whereType(Type::PODCAST);
    }

    public function scopeVideos($query): Builder
    {
        return $query->whereType(Type::VIDEO);
    }

    public function scopeLatestPublished($query): Builder
    {
        return $query->whereStatus(Status::PUBLISHED)->orderBy('published_at');
    }
}
