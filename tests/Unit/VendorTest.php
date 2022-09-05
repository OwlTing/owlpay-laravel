<?php

namespace Tests\Unit;

use Owlting\OwlPay\Exceptions\UnauthorizedException;
use Owlting\OwlPay\OwlPay;
use Tests\TestCase;


class VendorTest extends TestCase
{
    // updateVendor
    // deleteVendor

    public function test_create_vendor()
    {
        // Arrange
        $body = self::$vendorMockData;
        $this->mockGuzzle('post', $body);

        //Act
        $target = new OwlPay();
        $response = $target->createVendor([
            'country_iso' => 'TW',
            'email' => 'owlpay@owlting.com'
        ]);

        //Assert
        $this->assertEquals($response->getLastResponse(), $body);

        $this->assertArrayHasKey('uuid', $response);
        $this->assertArrayHasKey('email', $response);
    }

    public function test_update_vendor()
    {
        // Arrange
        $body = self::$vendorMockData;
        $this->mockGuzzle('put', $body);

        //Act
        $target = new OwlPay();
        $response = $target->updateVendor('ven_QrkcpJ1Z1hZVHKF47tFsDsj', [
            'application_vendor_uuid' => 'Mock_updated_description',
        ]);

        //Assert
        $this->assertEquals($response->getLastResponse(), $body);

        $this->assertArrayHasKey('uuid', $response);
        $this->assertArrayHasKey('email', $response);
    }

    public function test_get_vendors()
    {
        // Arrange
        $body = self::$vendorsMockData;
        $this->mockGuzzle('get', $body);

        //Act
        $target = new OwlPay();
        $response = $target->getVendors();

        $this->assertEquals($response->getLastResponse(), $body);
    }

    public function test_get_vendor_detail()
    {
        //Arrange
        $body = self::$vendorMockData;
        $this->mockGuzzle('get', $body);

        //Act
        $target = new OwlPay();
        $response = $target->getVendorDetail('ven_QrkcpJ1Z1hZVHKF47tFsDsj');

        //Assert
        $this->assertEquals($response->getLastResponse(), $body);
        $this->assertArrayHasKey('uuid', $response);
        $this->assertArrayHasKey('email', $response);
    }
}
