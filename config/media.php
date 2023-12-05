<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Route Options
    |--------------------------------------------------------------------------
    |
    | Define the options to use for media routes.
    |
    */
    'route_options' => [
        'as' => 'media.',
        'prefix' => 'media',
        'middleware' => ['web', 'auth'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Storage
    |--------------------------------------------------------------------------
    |
    | Define storage disk and path for media.
    |
    */
    'storage' => [
        'images' => [
            'disk' => 'public',
            'path' => 'media/images',
        ],

        'videos' => [
            'disk' => 'public',
            'path' => 'media/videos',
        ],
    ],
];
