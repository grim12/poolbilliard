<?php

namespace Tests\Feature;

use Tests\TestCase;

class DeployRunnerTest extends TestCase
{
    public function test_endpoint_404s_when_no_token_is_configured(): void
    {
        config(['deploy.runner_token' => null]);

        $this->get('/system/deploy-runner?token=anything&run=migrate-status')->assertNotFound();
    }

    public function test_endpoint_404s_with_a_wrong_token(): void
    {
        config(['deploy.runner_token' => 'secret']);

        $this->get('/system/deploy-runner?token=wrong&run=migrate-status')->assertNotFound();
    }

    public function test_endpoint_404s_for_a_command_outside_the_allow_list(): void
    {
        config(['deploy.runner_token' => 'secret']);

        $this->get('/system/deploy-runner?token=secret&run=migrate:fresh')->assertNotFound();
    }

    public function test_endpoint_runs_an_allowed_command_with_the_correct_token(): void
    {
        config(['deploy.runner_token' => 'secret']);

        $response = $this->get('/system/deploy-runner?token=secret&run=migrate-status');

        $response->assertOk();
        $response->assertSee('Exit code: 0');
    }

    public function test_endpoint_is_reachable_even_when_the_site_is_locked(): void
    {
        config(['sitelock.enabled' => true, 'deploy.runner_token' => 'secret']);

        $this->get('/system/deploy-runner?token=secret&run=migrate-status')->assertOk();
    }
}
