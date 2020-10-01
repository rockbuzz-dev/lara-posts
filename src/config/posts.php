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
