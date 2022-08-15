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

    protected static $vendorMockData = [
        "data" => [
            "uuid" => "ven_xxxxx",
            "object" => "vendor",
            "application_vendor_uuid" => null,
            "name" => "Vendor",
            "email" => "owlpay@owlting.com",
            "description" => "",
            "note" => null,
            "is_removed" => false,
            "is_active" => false,
            "is_vendor_kyc_passed" => false,
            "status" => "uncheck",
            "kyc_status_list" => [
              "remit_info" => [
                "status" => "unchecked",
                "is_allow_update" => true,
              ],
            ],
            "country_iso" => "TW",
            "is_invited" => false,
            "is_registered" => false,
            "remit_info" => [],
        ],
        "status" => 200,
    ];

    protected static $vendorsMockData = [
        "data" => [
            [
                "uuid" => "ven_xxxxx",
                "object" => "vendor",
                "application_vendor_uuid" => null,
                "name" => "Vendor",
                "email" => "owlpay@owlting.com",
                "description" => "",
                "note" => null,
                "is_removed" => false,
                "is_active" => false,
                "is_vendor_kyc_passed" => false,
                "status" => "uncheck",
                "kyc_status_list" => [
                "remit_info" => [
                    "status" => "unchecked",
                    "is_allow_update" => true,
                ],
                ],
                "country_iso" => "TW",
                "is_invited" => false,
                "is_registered" => false,
                "remit_info" => [],
            ]
        ],
        "status" => 200,
        "paginaton" => [
            "total" => 1,
            "count" => 12,
            "perPage" => 12,
            "currentPage" => 1,
            "totalPages" => 52,
            "links" => [
              "next" => "http://owlpay.owlting.worker/api/v1/platform/tunnel/vendors?q=%2Fapi%2Fv1%2Fplatform%2Ftunnel%2Fvendors&page=2"
            ],
        ],
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

    protected static $vendorRemitInfoMockData = [
        "data" => [
            [
                "payout_gateway" => "Cathay United Bank - Global MyB2B",
                "status" => "checking",
                "sort" => 0,
                "is_enable" => true,
                "detail" => [
                    "country_code" => "TW",
                    "bank_code" => "013",
                    "branch_code" => "2631",
                    "account" => "123456789",
                    "account_name"=> "OwlPay Inc.",
                    "currency" => "TWD",
                ],
            ],
        ],
        "status" => 200
    ];
}