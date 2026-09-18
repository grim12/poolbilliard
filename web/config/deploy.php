<?php

return [

    /**
     * Lets App\Http\Controllers\DeployRunnerController run a small allow-list of artisan
     * commands over HTTP — for the FTP-only test host that has no SSH/shell (see README
     * "Nasazení na testovací prostředí"). Unset/empty in .env: the endpoint always 404s, so it's
     * inert unless deliberately turned on. Set it only while actually deploying, then remove it
     * from .env again.
     */
    'runner_token' => env('DEPLOY_RUNNER_TOKEN'),

];
