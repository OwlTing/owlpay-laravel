<?php

namespace Tests;

use \Mockery as m;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{

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

    protected static $orderMockData = [
        'data' => [
            'status' => 'owlpay.received_order123123',
            'currency' => 'TWD',
            "order_serial" => "test",
            "total" => 1000,
            "is_paid" => false,
            "paid_time_at" => null,
            "notified_time_at" => null,
            "meta_data" => [],
            'events' => [
                "type" => "order_receive",
                "block_number" => null,
                "transaction_hash" => null,
                "created_at" => "2020-09-18T09:37:22+00:00",
            ],
            'order_token' => 'ord_5d4f192692e44fb36ae4f8a4e5d0f01f377e4ae0caf2f1afc8e60d01b87ab9b5',
            'description' => null,

        ],
        'status' => 200,
    ];

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
