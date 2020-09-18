<?php

namespace Owlting\OwlPay;

use Owlting\OwlPay\Objects\Order;

class OwlPay
{
    /**
     * @param $order_serial
     * @param $currency
     * @param $total
     * @param array $meta_data
     * @param null $vendor_uuid
     * @param null $description
     * @param bool $is_force_create
     * @throws Exceptions\InvalidRequestException
     * @throws Exceptions\MissingParameterException
     */
    public function createOrder($order_serial, $currency, $total, $meta_data = [], $vendor_uuid = null, $description = null, $is_force_create = false)
    {
        $input = compact(
            'order_serial',
            'currency',
            'total',
            'meta_data',
            'vendor_uuid',
            'description',
            'is_force_create'
        );

        $input = array_filter($input);

        $order = new Order();

        $order->create($input);

        return $order;
    }

    /**
     * @param $order_token
     * @return Order
     * @throws Exceptions\InvalidRequestException
     */
    public function getOrderDetail($order_token)
    {
        $order = new Order();

        return $order->detail($order_token);
    }
}
