<?php

return [
    'tables' => [
        'posts' => 'posts',
        'authors' => 'users'
    ],
    'author_reference_uuid' => true,
    'models' => [
        'post' => \Rockbuzz\LaraPosts\Models\Post::class,
        'author' => \App\User::class,
    ]
];
