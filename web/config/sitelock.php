<?php

return [

    /**
     * null (default): locked everywhere except local dev/tests. true/false: force the state
     * regardless of environment — set to false in production's .env once the site goes live.
     */
    'enabled' => env('SITE_LOCK_ENABLED'),

];
