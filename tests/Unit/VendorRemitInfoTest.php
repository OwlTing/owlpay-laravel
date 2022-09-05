<?php

namespace Tests\Unit;

use Owlting\OwlPay\Exceptions\UnauthorizedException;
use Owlting\OwlPay\OwlPay;
use Tests\TestCase;


class VendorRemitInfoTest extends TestCase
{
    public function test_get_vendor_remit_info()
    {
        // Arrange
        $body = self::$vendorsMockData;
        $this->mockGuzzle('get', $body);

        //Act
        $target = new OwlPay();
        $vendor_uuid = 'ven_xxxxxxxxx';

        $response = $target->getVendorRemitInfo($vendor_uuid);

        $this->assertEquals($response->getLastResponse(), $body);
    }   

    public function test_post_apply_vendor_remit_info()
    {
        // Arrange
        $body = self::$vendorRemitInfoMockData;
        $this->mockGuzzle('post', $body);

        //Act
        $target = new OwlPay();
        $vendor_uuid = 'ven_xxxxxxxxx';

        $response = $target->createVendorRemitInfo($vendor_uuid, [
            'payout_channel' => 'cathay',
            'applicant' => 'company',
            'aml_data' => [
                'currency' => 'TWD',
                'companyName' => 'OwlPay Inc.',
                'businessAddressCity' => '新北市',
                'businessAddressArea' => '新店區',
                'businessAddress' => '北新路三段225號3樓',
                'companyPhoneCode' => 'TW',
                'companyPhoneNumber' => '912345678',
                'companyEmail' => 'owlpay-sdk@owlpay.com',
                'companyId' => $vendor_uuid,
                'customName' => 'Vendor Remit Info',
                'bankCountry' => 'TW',
                'bankCode' => '013',
                'branchCode' => '2631',
                'swiftCode' => 'BKTWTWTPXXX',
                'accountName' => 'OwlPay Inc.',
                'account' => '123456789',
            ]
        ]);

        $this->assertEquals($response->getLastResponse(), $body);
    }
}