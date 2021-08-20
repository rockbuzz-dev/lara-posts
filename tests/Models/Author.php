<?php

namespace Tests\Models;

use Rockbuzz\LaraUuid\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Rockbuzz\LaraPosts\Traits\HavePosts;

class Author extends Model
{
    use Uuid, HavePosts;

    public $incrementing = false;
}
