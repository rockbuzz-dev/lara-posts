<?php

namespace Rockbuzz\LaraPosts\Traits;

trait HasPosts
{
    public function posts()
    {
        return $this->hasMany(config('posts.models.post'));
    }
}
