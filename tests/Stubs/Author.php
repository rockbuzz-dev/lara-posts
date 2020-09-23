<?php

namespace Tests\Stubs;

use Rockbuzz\LaraUuid\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Rockbuzz\LaraPosts\Traits\HasPosts;

class Author extends Model
{
    use Uuid, HasPosts;

    public $incrementing = false;
}
