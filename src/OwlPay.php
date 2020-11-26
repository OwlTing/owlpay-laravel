<?php

namespace Owlting\OwlPay;

use Owlting\OwlPay\Exceptions\NotFoundException;
use Owlting\OwlPay\Exceptions\OwlPayException;
use Owlting\OwlPay\Exceptions\UnauthorizedException;
use Owlting\OwlPay\Exceptions\UnknownException;
use Owlting\OwlPay\Exceptions\ClassNotFoundException;
use Owlting\OwlPay\Objects\BaseObject;
use Owlting\OwlPay\Objects\Order;
use Owlting\OwlPay\Objects\VendorInvite;

class OwlPay
{
    protected static $errors_map = [
        -1 => UnknownException::class,
        401 => UnauthorizedException::class,
        404 => NotFoundException::class,

        10404 => ClassNotFoundException::class
    ];

    public function __call($name, $args)
    {
        $object = __NAMESPACE__ . '\\objects\\' . $this->camelize($name);

        if (!class_exists($object))
            throw new self::$errors_map[10404];

        return new $object();
    }

    /**
     * @param $order_serial
     * @param $currency
     * @param $total
     * @param array $meta_data
     * @param null $vendor_uuid
     * @param null $description
     * @param bool $is_force_create
     * @return Order
     * @throws Exceptions\InvalidRequestException
     * @throws Exceptions\MissingParameterException
     * @throws NotFoundException
     * @throws OwlPayException
     * @throws UnauthorizedException
     * @throws UnknownException
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
     * @throws NotFoundException
     * @throws OwlPayException
     * @throws UnauthorizedException
     * @throws UnknownException
     */
    public function getOrderDetail($order_token)
    {
        $order = new Order();

        $order->detail($order_token);

        $this->checkResponse($order);

        return $order;
    }

    /**
     * @param $args
     * @return VendorInvite
     * @throws Exceptions\InvalidRequestException
     * @throws Exceptions\MissingParameterException
     * @throws NotFoundException
     * @throws OwlPayException
     * @throws UnauthorizedException
     * @throws UnknownException
     */
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

    private function camelize($input, $separator = '_')
    {
        return str_replace($separator, '', ucwords($input, $separator));
    }
}
