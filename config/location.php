<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Location Data Sync Source
    |--------------------------------------------------------------------------
    |
    | The base URL where the JSON data for countries, states, and cities
    | is fetched from during the sync command.
    |
    */
    'sync_url' => env('LOCATION_SYNC_URL', ''),
];
