<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Geolocation Service
    |--------------------------------------------------------------------------
    |
    | This option controls the default geolocation service when using this
    | feature. You can swap this service on the fly if required.
    |
    */
    'default' => env('GEOLOCATABLE_SERVICE', 'cloudflare'),

    /*
    |--------------------------------------------------------------------------
    | Geolocation Configurations
    |--------------------------------------------------------------------------
    |
    | Here you can configure the required credentials and additional metadata
    | for each supported geolocation service.
    |
    | Supported services: "cloudflare", "ip2location", "ipapi.co", "ip-api",
                          "ipdata", "maxmind-database", "maxmind-geoip"
    |
    */
    'services' => [

        'cloudflare' => [
            'driver' => 'cloudflare',
        ],

        'ip2location' => [
            'driver' => 'ip2location',
            'key' => env('IP2LOCATION_KEY'),
        ],

        // ipapi.co
        'ipapico' => [
            'driver' => 'ipapico',
        ],

        // ipapi.com
        'ipapicom' => [
            'driver' => 'ipapicom',
            'key' => env('IPAPI_KEY'),
        ],

        // ip-api.com
        'ipdashapi' => [
            'driver' => 'ipdashapi',
        ],

        'ipdata' => [
            'driver' => 'ipdata',
            'key' => env('IPDATA_KEY'),
        ],

        'maxmind-database' => [
            'driver' => 'maxmind-database',
            'account_id' => env('MAXMIND_ACCOUNT_ID'),
            'license_key' => env('MAXMIND_LICENSE_KEY'),
            'path' => env('MAXMIND_DATABASE_PATH', 'maxmind.mmdb'),
        ],

        'maxmind-geoip' => [
            'driver' => 'maxmind',
            'level' => env('MAXMIND_LEVEL', 'city'), // supports "country", "city" and "insights"
            'account_id' => env('MAXMIND_ACCOUNT_ID'),
            'license_key' => env('MAXMIND_LICENSE_KEY'),
        ],

    ],

];
