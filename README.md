# OwlPay PHP bindings

The OwlPay PHP library provides convenient access to the OwlPay API from
applications written in the PHP language.

## Requirements

PHP 7.1 and later.

## Composer

You can install the bindings via [Composer](http://getcomposer.org/). Run the following command:

```bash
composer require owlting/owlpay-php
```

To use the bindings, use Composer's [autoload](https://getcomposer.org/doc/01-basic-usage.md#autoloading):

```php
require_once('vendor/autoload.php');
```

## Dependencies

The bindings require the following extensions in order to work properly:

-   [`curl`](https://secure.php.net/manual/en/book.curl.php), although you can use your own non-cURL client if you prefer
-   [`json`](https://secure.php.net/manual/en/book.json.php)
-   [`mbstring`](https://secure.php.net/manual/en/book.mbstring.php) (Multibyte String)

If you use Composer, these dependencies should be handled automatically. If you install manually, you'll want to make sure that these extensions are available.

## Documentation


## Getting Started

add secret in .env

```
OWLPAY_API_URL=http://owlpay.owlting.localhost
OWLPAY_APPLICATION_SECRET=MY_SECRET.....
```


Simple usage looks like:

```php
$order_serial = 'OWL0001';
$currency = 'TWD';
$meta_data = []; 

\Owlting\OwlPay\Facades\OwlPay::createOrder(
    $order_serial,
    $currency,
    $meta_data
);
```

