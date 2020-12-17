<?php

namespace Owlting\OwlPay;

use Owlting\OwlPay\Exceptions\NotFoundException;
use Owlting\OwlPay\Exceptions\OwlPayException;
use Owlting\OwlPay\Exceptions\UnauthorizedException;
use Owlting\OwlPay\Exceptions\UnknownException;
use Owlting\OwlPay\Exceptions\ClassNotFoundException;
use Owlting\OwlPay\Objects\BaseObject;
use Owlting\OwlPay\Objects\Interfaces\SecretInterface;
use Owlting\OwlPay\Objects\Order;
use Owlting\OwlPay\Objects\Traits\SecretTrait;
use Owlting\OwlPay\Objects\VendorInvite;

class OwlPay implements SecretInterface
{
    use SecretTrait;

    protected static $errors_map = [
        -1 => UnknownException::class,
        401 => UnauthorizedException::class,
        404 => NotFoundException::class,

        10404 => ClassNotFoundException::class
    ];

    public function __call($name, $args)
    {
        $object = __NAMESPACE__ . '\\Objects\\' . $this->camelize($name);

        if (!class_exists($object))
            throw new self::$errors_map[10404];

        return new $object();
    }

    /**
     * @param $order_serial
     * @param $currency
     * @param $total
     * @param null $description
     * @param array $vendor
     * @param array $meta_data
     * @param bool $is_force_create
     * @return Order
     * @throws NotFoundException
     * @throws OwlPayException
     * @throws UnauthorizedException
     * @throws UnknownException
     */
    public function createOrder($order_serial,
                                $currency,
                                $total,
                                $description = null,
                                $vendor = [],
                                $meta_data = [],
                                $is_force_create = false)
    {
        $input = compact(
            'order_serial',
            'currency',
            'total',
            'description',
            'vendor',
            'meta_data',
            'is_force_create'
        );

        $input = array_filter($input);

        $order = new Order();

        if (empty($this->secret)) {
            $order->setSecret($this->secret);
        }

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

        if (empty($this->secret)) {
            $order->setSecret($this->secret);
        }

        $order->detail($order_token);

        $this->checkResponse($order);

        return $order;
    }

    /**
     * @param $args
     * @return VendorInvite
     * @throws NotFoundException
     * @throws OwlPayException
     * @throws UnauthorizedException
     * @throws UnknownException
     */
    public function createVendorInvite($args)
    {
        $vendorInvite = new VendorInvite();

        if (empty($this->secret)) {
            $vendorInvite->setSecret($this->secret);
        }

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
