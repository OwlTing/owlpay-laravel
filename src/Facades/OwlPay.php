<?php

namespace Owlting\OwlPay\Facades;

use Illuminate\Support\Facades\Facade;
use Owlting\OwlPay\OwlPay as OwlPayItem;

/**
 * @method static \Owlting\OwlPay\Objects\Order createOrder($order_serial, $currency, $total, $meta_data = [], $vendor_uuid = null, $description = null, $is_force_create = false)
 * @method static \Owlting\OwlPay\Objects\Order getOrderDetail($order_token)
 *
 * Class OwlPay
 * @package Owlting\OwlPay\Facades
 */
class OwlPay extends Facade
{
    protected static function getFacadeAccessor()
    {
        return OwlPayItem::class;
    }
}
