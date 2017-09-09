<?php

return [
    'default' => env('CACHE_DRIVER', 'file'),

    'views'   => env('VIEW_CACHE_DRIVER', 'views'),

    'drivers' => [

        'apc' => [
            'adapter' => 'Apc',
        ],

        'memcached' => [
            'adapter' => 'Libmemcached',
            'servers' => [
                [
                    'host'   => env('MEMCACHED_HOST', '127.0.0.1'),
                    'port'   => env('MEMCACHED_PORT', 11211),
                    'weight' => env('MEMCACHED_WEIGHT', 100),
                ]
            ],
        ],

        'file' => [
            'adapter'  => 'File',
            'cacheDir' => BASE_PATH . '/cache/data'
        ],

        'views' => [
            'adapter'  => 'File',
            'cacheDir' => BASE_PATH . '/cache/views'
        ],

        'redis' => [
            'adapter' => 'Redis',
            'host'    => env('REDIS_HOST', '127.0.0.1'),
            'port'    => env('REDIS_PORT', 6379),
            'index'   => env('REDIS_INDEX', 0),
        ],

        'memory' => [
            'adapter' => 'Memory',
        ],
    ],

    'prefix' => env('CACHE_PREFIX', 'forum_cache_'),

    'lifetime' => env('CACHE_LIFETIME', 86400),
];