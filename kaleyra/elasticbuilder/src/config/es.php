<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Elasticsearch Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the Elasticsearch connections below you wish
    | to use as your default connection for all work. Of course.
    |
    */

    'default' => env('ELASTIC_CONNECTION', 'default'),

    /*
    |--------------------------------------------------------------------------
    | Elasticsearch Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the Elasticsearch connections setup for your application.
    | Of course, examples of configuring each Elasticsearch platform.
    |
    */

    'connections' => [

        'default' => [

            'servers' => [

                [
                    'host' => env('ELASTIC_HOST', '127.0.0.1'),
                    'port' => env('ELASTIC_PORT', 9200),
                    'user' => env('ELASTIC_USER', ''),
                    'pass' => env('ELASTIC_PASS', ''),
                    'scheme' => env('ELASTIC_SCHEME', 'http'),
                ]

            ],

            'index' => env('ELASTIC_INDEX', 'my_index'),

            // Elasticsearch handlers
            // 'handler' => new MyCustomHandler(),
            
            'logging' => [
                'enabled'   => env('ELASTIC_LOGGING_ENABLED',false),
                'level'     => env('ELASTIC_LOGGING_LEVEL','all'),
                'location'  => env('ELASTIC_LOGGING_LOCATION',base_path('storage/logs/elasticsearch.log'))
            ],            
        ]
    ],

    

];
