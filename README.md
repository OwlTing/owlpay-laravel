# OwlPay PHP bindings

The OwlPay PHP library provides convenient access to the OwlPay API from
applications written in the PHP language.

## Requirements

PHP 5.6.0 and later.


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

Simple usage looks like:

```php
$owlpay = new \Owlting\OwlPayClient('pk_test_TRd7T4JHznaKYK2mUQznHclSnAaFJJHlbo6iILpNKKZirJdzRBw8qC25gYUeycc4DCidip58f9NBcy12717Fkho8b76buqUI1Mtlme2p');
$order = $owlpay->orders->create([
    'order_serial' => 'OWLP123442123',
    'total' => 100,
    'currency' => 'TWD'
]);
echo $order;
```

