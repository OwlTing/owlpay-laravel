# OwlPay Laravel SDK
OwlPay SDK provides ease to use APIs and internal importing for PHP, it's willing provide several use cases following.

## Warning
This package is developing now, if we have a huge changes, the semi version is going to modify.

## Requirements
* PHP 7 or later.
* Laravel
* curl extension
* json extension
* mbstring extension

## Installation
### By composer
```json
    {
      "repositories": [
        {
          "type": "vcs",
          "url": "git@github.com:OwlTing/owlpay-php.git",
          "no-api": true
        }
      ]
    }
```

Next, install the package.
```bash
$ composer require owlting/owlpay-php
```

Also, you need to publish and configure the environment keys in your application.
```bash
$ php artisan vendor:publish --provider="Owlting\OwlPay\Providers\OwlPayServiceProvider"
```
If you are using laravel version less than 5.4, you need to manually install the provider in app.php

Finally, set the environment variables.

### Services endpoint
| environment    | endpoint                |
|---------|----------------------------|
| staging  | https://api-stage.owlpay.com/        |

```dotenv
OWLPAY_API_URL=http://owlpay.owlting.localhost
OWLPAY_APPLICATION_SECRET=MY_SECRET.....
```
## APIs
### Import vendors
```php
use Owlting\OwlPay\Facades\OwlPay;
use Illuminate\Support\Str;

Vendor::query()->get()
    ->map(function($vendor) {
        OwlPay::vendor_invite()->create([
            'email' => $vendor->email, //hello.sale@owlting.com
            'is_owlpay_send_email' => false,
            'vendor' => [
                'customer_vendor_uuid' => $vendor->vendor_number, // Unique vendor id in application.
                'name' => $vendor->vendor_name, // 奧丁丁皮箱旅行有限公司
                'description' => $vendor->vendor_name, // you can define any.
                'remit_info' => [
                    'country_iso' => Str::upper($vendor->country_code), // TW
                    'bank_name' => $vendor->bank_name, // 國泰世華
                    'bank_subname' => $vendor->branch_name, // 永春分行
                    'bank_code' => $vendor->bank_code, // 013
                    'bank_subcode' => $vendor->bank_subcode, // 0785
                    'bank_account' => $vendor->account_number, // 123456789102
                    'bank_account_name' => $vendor->account_name, // 奧丁丁皮箱旅行有限公司
                ]
            ]
        ]);
    });
```

### Import order
```php
use Owlting\OwlPay\Facades\OwlPay;

$order = Order::first();
$customer_vendor_uuid = optional($order->vendor)->vendor_number; // be sure having vendor number.
$meta_data = [];

OwlPay::createOrder(
    $order->order_number, // OTR2020120700004
    $order->currency, // TWD
    $order->total, // paid price, 100.00
    date('Y-m-d\TH:i:sP', $order->created_at), // order created_at with timezone 2020-12-21T23:24:29-05:00
    $order->description, // any or order_number
    $customer_vendor_uuid ?? '', // vendor uuid
    $meta_data, // empty for now
    $isForceCreate = false // needs to forcing create?
);
```



