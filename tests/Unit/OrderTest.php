<?php

namespace Tests\Unit;

use Owlting\OwlPay\Exceptions\UnauthorizedException;
use Owlting\OwlPay\OwlPay;
use Tests\TestCase;


class OrderTest extends TestCase
{
    public function test_create_order()
    {
        //Arrange
        $body = self::$orderMockData;
        $this->mockGuzzle('post', $body);

        //Act
        $target = new OwlPay();
        $response = $target->createOrder('test', 'TWD', 1000);

        //Assert
        $this->assertEquals($response->getLastResponse(), $body);

        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('currency', $response);
        $this->assertArrayHasKey('order_serial', $response);
        $this->assertArrayHasKey('total', $response);
        $this->assertArrayHasKey('is_paid', $response);
        $this->assertArrayHasKey('paid_time_at', $response);
        $this->assertArrayHasKey('notified_time_at', $response);
        $this->assertArrayHasKey('meta_data', $response);
        $this->assertArrayHasKey('events', $response);
        $this->assertArrayHasKey('order_token', $response);
        $this->assertArrayHasKey('description', $response);
    }

    public function test_create_order_unauthorized()
    {
        //Arrange
        $body = self::$unauthorizedMockData;
        $this->mockGuzzle('post', $body);

        $this->expectException(UnauthorizedException::class);

        //Act
        $target = new OwlPay();
        $response = $target->createOrder('test', 'TWD', 1000);
        //Assert
    }

    public function test_show_detail()
    {
        //Arrange
        $body = self::$orderMockData;
        $this->mockGuzzle('get', $body);

        //Act
        $target = new OwlPay();
        $response = $target->getOrderDetail('ord_799956254af05adb86b0fa02bf7dbce3e351e5cb2f7d8c27dfd8745c36a7c40e');

        //Assert
        $this->assertEquals($response->getLastResponse(), $body);

        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('currency', $response);
        $this->assertArrayHasKey('order_serial', $response);
        $this->assertArrayHasKey('total', $response);
        $this->assertArrayHasKey('is_paid', $response);
        $this->assertArrayHasKey('paid_time_at', $response);
        $this->assertArrayHasKey('notified_time_at', $response);
        $this->assertArrayHasKey('meta_data', $response);
        $this->assertArrayHasKey('events', $response);
        $this->assertArrayHasKey('order_token', $response);
        $this->assertArrayHasKey('description', $response);
    }

    public function test_show_detail_unauthorized()
    {
        //Arrange
        $body = self::$unauthorizedMockData;
        $this->mockGuzzle('get', $body);

        $this->expectException(UnauthorizedException::class);

        //Act
        $target = new OwlPay();
        $response = $target->getOrderDetail('ord_799956254af05adb86b0fa02bf7dbce3e351e5cb2f7d8c27dfd8745c36a7c40e');

        //Assert
    }

    /**
     * @group order.cancel
     */
    public function test_cancel_orders()
    {
        //Arrange
        $body = self::$orderMockData;
        $this->mockGuzzle('put', $body);

        //Act
        $target = new OwlPay();
        $response = $target->cancelOrder(['OBEXXXOOO12345']);

        //Assert
        $this->assertEquals($response->getLastResponse(), $body);

        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('currency', $response);
        $this->assertArrayHasKey('order_serial', $response);
        $this->assertArrayHasKey('total', $response);
        $this->assertArrayHasKey('is_paid', $response);
        $this->assertArrayHasKey('paid_time_at', $response);
        $this->assertArrayHasKey('notified_time_at', $response);
        $this->assertArrayHasKey('meta_data', $response);
        $this->assertArrayHasKey('events', $response);
        $this->assertArrayHasKey('order_token', $response);
        $this->assertArrayHasKey('description', $response);
    }
}
