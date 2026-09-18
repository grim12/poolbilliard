<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\BufferedOutput;

/**
 * Runs a fixed allow-list of artisan commands over HTTP — for a host with FTP-only access and no
 * SSH/shell (see README "Nasazení na testovací prostředí"). The command name is never taken from
 * the request beyond picking a key below, so this can't be turned into running an arbitrary or
 * destructive artisan command. Guarded by DEPLOY_RUNNER_TOKEN (config/deploy.php) — the route
 * 404s outright whenever that env var is unset, so it's inert unless deliberately turned on for a
 * deploy, and should be removed from .env again once you're done.
 */
class DeployRunnerController extends Controller
{
    private const ALLOWED_COMMANDS = [
        'migrate' => ['migrate', ['--force' => true]],
        'migrate-status' => ['migrate:status', []],
        'storage-link' => ['storage:link', []],
        'db-seed' => ['db:seed', ['--force' => true]],
        'optimize-clear' => ['optimize:clear', []],
    ];

    public function run(Request $request): Response
    {
        $token = config('deploy.runner_token');

        abort_unless(
            filled($token) && hash_equals($token, (string) $request->query('token')),
            404
        );

        $command = $request->query('run');

        abort_unless(is_string($command) && array_key_exists($command, self::ALLOWED_COMMANDS), 404);

        [$artisanCommand, $arguments] = self::ALLOWED_COMMANDS[$command];

        $output = new BufferedOutput;
        $exitCode = Artisan::call($artisanCommand, $arguments, $output);

        return response("Exit code: {$exitCode}\n\n".$output->fetch(), 200, [
            'Content-Type' => 'text/plain',
        ]);
    }
}
