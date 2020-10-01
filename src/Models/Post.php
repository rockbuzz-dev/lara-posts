<?php

namespace Rockbuzz\LaraPosts\Models;

use Rockbuzz\LaraUuid\Traits\Uuid;
use Spatie\Sluggable\{HasSlug, SlugOptions};
use Rockbuzz\LaraPosts\Enums\{Status, Type};
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\EloquentSortable\{Sortable, SortableTrait};
use Illuminate\Database\Eloquent\{Builder, Model, SoftDeletes};
use Spatie\SchemalessAttributes\{SchemalessAttributes, SchemalessAttributesTrait};

class Post extends Model implements Sortable
{
    use Uuid, HasSlug, SortableTrait, SoftDeletes, SchemalessAttributesTrait;

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

    public function getRouteKeyName()
    {
        return config('posts.route_key_name');
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

    public function isApproved(): bool
    {
        return $this->status === Status::APPROVED;
    }

    public function isPublished()
    {
        return $this->isApproved() && $this->published_at <= now();
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

    public function scopeDraft($query): Builder
    {
        return $query->whereStatus(Status::DRAFT);
    }

    public function scopeModerate($query): Builder
    {
        return $query->whereStatus(Status::MODERATE);
    }

    public function scopeApproved($query): Builder
    {
        return $query->whereStatus(Status::APPROVED);
    }

    public function scopePublished($query): Builder
    {
        return $query->where('published_at', '<=', now())
            ->where('status', Status::APPROVED);
    }

    public function scopeArticle($query): Builder
    {
        return $query->whereType(Type::ARTICLE);
    }

    public function scopePodcast($query): Builder
    {
        return $query->whereType(Type::PODCAST);
    }

    public function scopeVideo($query): Builder
    {
        return $query->whereType(Type::VIDEO);
    }

    public function scopeLatestPublished($query): Builder
    {
        return $query->whereStatus(Status::APPROVED)->orderBy('published_at');
    }
}
