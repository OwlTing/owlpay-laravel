# OwlPay Laravel SDK
OwlPay Laravel SDK provides ease to use APIs and internal importing for Laravel, it's provide several use cases following.

## Warning
This package is developing now, if we have a huge changes, the semi version is going to modify.

## Requirements
* PHP >= 7.3
* PHP extension required
  * curl extension
  * json extension
  * mbstring extension
* Laravel >= 5.4

## Installation
Also, you need to publish and configure the environment keys in your application.
```bash
$ php artisan vendor:publish --provider="Owlting\OwlPay\Providers\OwlPayServiceProvider"
```

Finally, set the environment variables.

```dotenv
OWLPAY_API_URL=https://api.owlpay.com
OWLPAY_APPLICATION_SECRET=<OWLPAY_APPLICATION_SECRET>
```

Get `OWLPAY_APPLICATION_SECRET` according to the tutorial article
https://docs.owlpay.com/owlpay-guideline/zh/page-introduction/role_company/developer.html#_4-%E8%A8%AD%E5%AE%9A-api-key

### Send order to OwlPay
```php
use Owlting\OwlPay\Facades\OwlPay;

$order = Order::first();
$meta_data = [
  'sku' => 'SKU#1234',
];

OwlPay::createOrder([
    'application_order_uuid' => $order->order_number, // order number from your application
    'currency' => $order->currency, // TWD
    'total' => $order->total, // paid price, 100.00
    'application_vendor_uuid' => $order->vendor_number, // vendor number from your application
    'meta_data' => $meta_data, // extra information with key-value format
]);
```

### Read more

[See OwlPay API Document](https://docs.owlpay.com/api/)



