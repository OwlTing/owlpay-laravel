<?php

namespace Tests\Unit;

use Owlting\OwlPay\Exceptions\UnauthorizedException;
use Owlting\OwlPay\OwlPay;
use Tests\TestCase;


class VendorInviteTest extends TestCase
{
    public function test_create_vendor_invite()
    {
        //Arrange
        $body = self::$vendorInviteMockData;
        $this->mockGuzzle('post', $body);

        //Act
        $target = new OwlPay();
        $response = $target->vendor_invite()->create([
            'email' => 'owlpay@owlting.com'
        ]);

        //Assert
        $this->assertEquals($response->getLastResponse(), $body);

        $this->assertArrayHasKey('vendor_uuid', $response);
        $this->assertArrayHasKey('connect_invite_hash', $response);
        $this->assertArrayHasKey('invite_url', $response);
        $this->assertArrayHasKey('email', $response);
        $this->assertArrayHasKey('expired_at', $response);
    }

    public function test_create_order_unauthorized()
    {
        //Arrange
        $body = self::$unauthorizedMockData;
        $this->mockGuzzle('post', $body);

        //Act
        $target = new OwlPay();
        $response = $target->vendor_invite()->create([
            'email' => 'owlpay@owlting.com'
        ]);

        $this->assertEquals($response->getLastResponse(), $body);
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
}
