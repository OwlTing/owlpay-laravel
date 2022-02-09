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
          "url": "git@github.com:OwlTing/owlpay-laravel.git",
          "no-api": true
        }
      ]
    }
```

Next, install the package.
```bash
$ composer require owlting/owlpay-laravel
```

Also, you need to publish and configure the environment keys in your application.
```bash
$ php artisan vendor:publish --provider="Owlting\OwlPay\Providers\OwlPayServiceProvider"
```
If you are using laravel version less than 5.4, you need to manually install the provider in app.php

Finally, set the environment variables.

```dotenv
OWLPAY_API_URL=https://api.owlpay.com
OWLPAY_APPLICATION_SECRET=MY_SECRET.....
```
### Send order to OwlPay
```php
use Owlting\OwlPay\Facades\OwlPay;

$order = Order::first();
$meta_data = [];

OwlPay::createOrder([
    'order_serial' => $order->order_number, // OTR2020120700004
    'currency' => $order->currency, // TWD
    'total' => $order->total, // paid price, 100.00
    'vendor_uuid' => $vendor_uuid ?? null, // owlpay vendor uuid
    'meta_data' => $meta_data, // extra information with key-value format
]);
```



