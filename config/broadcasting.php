<?php

return [
    /*
|--------------------------------------------------------------------------
| Default Broadcaster
|--------------------------------------------------------------------------
|
| This option controls the default broadcaster that will be used by the
| framework when an event needs to be broadcast. You may set this to
| any of the connections defined in the "connections" array below.
|
| Supported: "pusher", "redis", "log", "null"
|
*/

    'default' => env('BROADCAST_DRIVER', 'null'),

    /*
|--------------------------------------------------------------------------
| Broadcast Connections
|--------------------------------------------------------------------------
|
| Here you may define all of the broadcast connections that will be used
| to broadcast events to other systems or over websockets. Samples of
| each available type of connection are provided inside this array.
|
*/

    'connections' => [
        'pusher' => [
            'driver' => 'pusher',
            'key' => env('PUSHER_APP_KEY'),
            'secret' => env('PUSHER_APP_SECRET'),
            'app_id' => env('PUSHER_APP_ID'),
            'options' => [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'useTLS' => true,
                'host' => 'jobs.test', // add this
                'port' => 6001, // and this
                'encrypted' => true, // also this
                'scheme' => 'https', // and this
                'curl_options' => [
                    // since we're only doing stuff locally this is fine
                    CURLOPT_SSL_VERIFYHOST => 0,
                    CURLOPT_SSL_VERIFYPEER => 0,
                ],
            ],
            'client_options' => [
                // for self signed ssl cert
                'verify' => false, // <- Added this
                // Guzzle client options: https://docs.guzzlephp.org/en/stable/request-options.html
            ],
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
        ],

        'log' => [
            'driver' => 'log',
        ],

        'null' => [
            'driver' => 'null',
        ],
    ],
];
