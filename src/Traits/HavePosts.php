<?php

namespace Rockbuzz\LaraPosts\Traits;

trait HavePosts
{
    public function posts()
    {
        return $this->hasMany(config('posts.models.post'));
    }
}
