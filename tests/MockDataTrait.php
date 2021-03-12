<?php


namespace Tests;


trait MockDataTrait
{
    protected static $unauthorizedMockData = [
        'status' => 401,
        'msg' => 'Unauthorized.',
    ];

    protected static $orderMockData = [
        'data' => [
            'order_status' => 'owlpay.received_order',
            'status' => 'owlpay.received_order',
            "order_serial" => "test",
            'currency' => 'TWD',
            "total" => 1000,
            "is_paid" => false,
            "paid_time_at" => null,
            "notified_time_at" => null,
            'vendor_uuid' => 'ven_oooo1234',
            'vendor_name' => 'vender name',
            'application_vendor_uuid' => '221312-123123-123123',
            "meta_data" => [
                [
                    'languge' => 'zh_tw'
                ]
            ],
            'events' => [
                [
                    "type" => "order_receive",
                    "block_number" => null,
                    "transaction_hash" => null,
                    "created_at" => "2020-09-18T09:37:22+00:00",
                ]
            ],
            'order_token' => 'ord_5d4f192692e44fb36ae4f8a4e5d0f01f377e4ae0caf2f1afc8e60d01b87ab9b5',
            'order_created_at' => '2020-12-31 00:00:01',
            'description' => null,
            'logs' => []
        ],
        'status' => 200,
    ];

    protected static $vendorInviteMockData = [
        'data' => [
            "vendor_uuid" => null,
            "connect_invite_hash" => "XXXXXXX",
            "invite_url" => "https://auth.owlting.com/project/xxxxx/force?email=owlpay%40owlting.com&redirect=http%3A%2F%2Fowlpay.owlting.worker%2Fauth%2Fvendor_login%2Fcallback%3Fowlpay_invite_token%3DXXXXXXX",
            "email" => "owlpay@owlting.com",
            "expired_at" => "2020-11-27T07:56:48+00:00"
        ],
        'status' => 200
    ];
}