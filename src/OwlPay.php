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
     * @param null $order_created_at
     * @param null $description
     * @param array|string $vendor
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
                                $order_created_at = null,
                                $description = null,
                                $vendor = null,
                                $meta_data = [],
                                $is_force_create = false): Order
    {
        $input = compact(
            'order_serial',
            'currency',
            'total',
            'order_created_at',
            'description',
            'vendor',
            'meta_data',
            'is_force_create'
        );

        if (!is_array($input['vendor'])) {
            $input['vendor'] = [
                'customer_vendor_uuid' => $input['vendor'],
            ];
        }

        $input = array_filter($input);

        $order = new Order();

        if (!empty($this->secret)) {
            $order->setSecret($this->secret);
        }

        $order->create($input);

        $this->checkResponse($order);

        return $order;
    }

    /**
     * @param $order_token
     * @return Order
     * @throws NotFoundException
     * @throws OwlPayException
     * @throws UnauthorizedException
     * @throws UnknownException
     */
    public function getOrderDetail($order_token): Order
    {
        $order = new Order();

        if (!empty($this->secret)) {
            $order->setSecret($this->secret);
        }

        $order->detail($order_token);

        $this->checkResponse($order);

        return $order;
    }

    /**
     * @param $order_tokens
     * @return Order
     * @throws NotFoundException
     * @throws OwlPayException
     * @throws UnauthorizedException
     * @throws UnknownException
     */
    public function cancelOrder($args): Order
    {
        $order = new Order();

        if (!empty($this->secret)) {
            $order->setSecret($this->secret);
        }

        if (isset($args['order_tokens']) && !is_array($args['order_tokens'])) {
            $args['order_tokens'] = [$args['order_tokens']];
        }

        if (isset($args['application_order_serials']) && !is_array($args['application_order_serials'])) {
            $args['application_order_serials'] = [$args['application_order_serials']];
        }

        $order->cancel($args);

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
    public function createVendorInvite($args): VendorInvite
    {
        $vendorInvite = new VendorInvite();

        if (!empty($this->secret)) {
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
