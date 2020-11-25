<?php

namespace Owlting\OwlPay;

use Owlting\OwlPay\Exceptions\NotFoundException;
use Owlting\OwlPay\Exceptions\OwlPayException;
use Owlting\OwlPay\Exceptions\UnauthorizedException;
use Owlting\OwlPay\Exceptions\UnknownException;
use Owlting\OwlPay\Objects\BaseObject;
use Owlting\OwlPay\Objects\Order;
use Owlting\OwlPay\Objects\VendorInvite;

class OwlPay
{
    protected static $errors_map = [
        -1 => UnknownException::class,
        401 => UnauthorizedException::class,
        404 => NotFoundException::class,
    ];

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

        $this->checkResponse($order);

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

        $order->detail($order_token);

        $this->checkResponse($order);

        return $order;
    }

    public function createVendorInvite($args)
    {
        $vendorInvite = new VendorInvite();

        $vendorInvite->create($args);

        $this->checkResponse($vendorInvite);

        return $vendorInvite;
    }

    /**
     * @param BaseObject $item
     * @return null
     * @throws UnknownException
     * @throws UnauthorizedException
     * @throws NotFoundException
     * @throws OwlPayException
     */
    protected function checkResponse(BaseObject $item)
    {
        $response = $item->getLastResponse();

        $response_status = $response['status'] ?? -1;

        /** @var OwlPayException|UnknownException|UnauthorizedException|NotFoundException $error */
        $error = self::$errors_map[$response_status] ?? null;

        if ($error !== null) {
            $error = new $error;
            $error->setResponse($response);
            throw $error;
        }
    }
}
