<?php

namespace Tests;

use \Mockery as m;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    use MockDataTrait;

    protected function setUp(): void
    {
        parent::setUp();

        // Your code here
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('owlpay.application_secret', env('OWLPAY_APPLICATION_SECRET'));
        $app['config']->set('owlpay.api_url', env('OWLPAY_API_URL'));
    }

    protected function mockGuzzle($method, $body)
    {
        $guzzleMock = m::mock(Client::class);
        $guzzleMock
            ->shouldReceive($method)
            ->once()
            ->andReturn(new Response(200, [], json_encode($body)));
        $this->app->instance(Client::class, $guzzleMock);
    }
}
