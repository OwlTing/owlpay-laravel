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
use Owlting\OwlPay\Objects\OrderTransfer;
use Owlting\OwlPay\Objects\Traits\SecretTrait;
use Owlting\OwlPay\Objects\Vendor;
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
     * @param  array  $args
     *
     * @return Order
     * @throws NotFoundException
     * @throws OwlPayException
     * @throws UnauthorizedException
     * @throws UnknownException
     */
    public function createOrder($args = []): Order
    {
        $order = new Order();

        if (!empty($this->secret)) {
            $order->setSecret($this->secret);
        }

        $order->create($args);

        $this->checkResponse($order);

        return $order;
    }

    /**
     *
     * @param  array  $args
     *
     * @return Order
     */
    public function mapOrderData($args = []): Order
    {
        $order = new Order();

        return $order->setOrderData($args);
    }

    /**
     * @param array $orders
     * @return Order
     * @throws NotFoundException
     * @throws OwlPayException
     * @throws UnauthorizedException
     * @throws UnknownException
     */
    public function createOrders(array $orders): Order
    {
        $order = new Order();

        $order->createBatch($orders);

        $this->checkResponse($order);

        return $order;
    }

    /**
     * @param $query
     * @return Order
     * @throws NotFoundException
     * @throws OwlPayException
     * @throws UnauthorizedException
     * @throws UnknownException
     */
    public function getOrders($query = []): Order
    {
        $order = new Order();

        if (!empty($this->secret)) {
            $order->setSecret($this->secret);
        }

        $order->all($query);

        $this->checkResponse($order);

        return $order;
    }

    /**
     * @param $order_uuid
     * @return Order
     * @throws NotFoundException
     * @throws OwlPayException
     * @throws UnauthorizedException
     * @throws UnknownException
     */
    public function getOrderDetail($order_uuid): Order
    {
        $order = new Order();

        if (!empty($this->secret)) {
            $order->setSecret($this->secret);
        }

        if (false === strpos($order_uuid, 'ord_')) {
            throw new OwlPayException('order prefix must be ord_');
        }

        $order->detail($order_uuid);

        $this->checkResponse($order);

        return $order;
    }

    /**
     * @param $args
     * @return Order
     * @throws NotFoundException
     * @throws OwlPayException
     * @throws UnauthorizedException
     * @throws UnknownException
     */
    public function cancelOrder($args = []): Order
    {
        $order = new Order();

        if (!empty($this->secret)) {
            $order->setSecret($this->secret);
        }

        if (isset($args['order_uuids']) && !is_array($args['order_uuids'])) {
            $args['order_uuids'] = [$args['order_uuids']];
        }

        if (isset($args['application_order_serials']) && !is_array($args['application_order_serials'])) {
            $args['application_order_serials'] = [$args['application_order_serials']];
        }

        $order->cancel($args);

        $this->checkResponse($order);

        return $order;
    }

    /**
     * @param $query
     * @return Vendor
     * @throws NotFoundException
     * @throws OwlPayException
     * @throws UnauthorizedException
     * @throws UnknownException
     */
    public function getVendors($query = []): Vendor
    {
        $vendor = new Vendor();

        if (!empty($this->secret)) {
            $vendor->setSecret($this->secret);
        }

        $vendor->all($query);

        $this->checkResponse($vendor);

        return $vendor;
    }

    /**
     * @param $vendor_uuid
     * @return Vendor
     * @throws NotFoundException
     * @throws OwlPayException
     * @throws UnauthorizedException
     * @throws UnknownException
     */
    public function getVendorDetail(string $vendor_uuid): Vendor
    {
        $vendor = new Vendor();

        if (!empty($this->secret)) {
            $vendor->setSecret($this->secret);
        }

        if (false === strpos($vendor_uuid, 'ven_')) {
            throw new OwlPayException('vendor prefix must be ven_');
        }

        $vendor->detail($vendor_uuid);

        $this->checkResponse($vendor);

        return $vendor;
    }

    /**
     * @param  array  $args
     *
     * @return Vendor
     * @throws NotFoundException
     * @throws OwlPayException
     * @throws UnauthorizedException
     * @throws UnknownException
     */
    public function createVendor($args = []): Vendor
    {
        $vendor = new Vendor();

        if (!empty($this->secret)) {
            $vendor->setSecret($this->secret);
        }

        $vendor->create($args);

        $this->checkResponse($vendor);

        return $vendor;
    }

    /**
     * @param $vendor_uuid
     * @param $args
     *
     * @return Vendor
     * @throws NotFoundException
     * @throws OwlPayException
     * @throws UnauthorizedException
     * @throws UnknownException
     */
    public function updateVendor($vendor_uuid, $args = []): Vendor
    {
        $vendor = new Vendor();

        if (!empty($this->secret)) {
            $vendor->setSecret($this->secret);
        }

        if (false === strpos($vendor_uuid, 'ven_')) {
            throw new OwlPayException('vendor prefix must be ven_');
        }

        $vendor->update($args, $vendor_uuid);

        $this->checkResponse($vendor);

        return $vendor;
    }

    /**
     * @param $vendor_uuid
     * @return Vendor
     * @throws NotFoundException
     * @throws OwlPayException
     * @throws UnauthorizedException
     * @throws UnknownException
     */
    public function deleteVendor($vendor_uuid)
    {
        $vendor = new Vendor();

        if (!empty($this->secret)) {
            $vendor->setSecret($this->secret);
        }

        if (false === strpos($vendor_uuid, 'ven_')) {
            throw new OwlPayException('vendor prefix must be ven_');
        }

        $vendor->delete($vendor_uuid);

        $this->checkResponse($vendor);

        return $vendor;
    }

    /**
     * @param $vendor_uuid
     * @param array $query
     * @return Vendor
     * @throws NotFoundException
     * @throws OwlPayException
     * @throws UnauthorizedException
     * @throws UnknownException
     */
    public function getVendorOrders($vendor_uuid, array $query = []): Vendor
    {
        $vendor = new Vendor();

        if (!empty($this->secret)) {
            $vendor->setSecret($this->secret);
        }

        if (false === strpos($vendor_uuid, 'ven_')) {
            throw new OwlPayException('vendor prefix must be ven_');
        }

        $vendor->vendor_orders($vendor_uuid, $query);

        $this->checkResponse($vendor);

        return $vendor;
    }

    /**
     * @param $args
     * @return VendorInvite
     * @throws NotFoundException
     * @throws OwlPayException
     * @throws UnauthorizedException
     * @throws UnknownException
     */
    public function createVendorInvite($args = []): VendorInvite
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
     * @param $args
     * @return OrderTransfer
     * @throws NotFoundException
     * @throws OwlPayException
     * @throws UnauthorizedException
     * @throws UnknownException
     */
    public function createOrdersTransfer($args = []): OrderTransfer
    {
        $orderTransfer = new OrderTransfer();

        if (!empty($this->secret)) {
            $orderTransfer->setSecret($this->secret);
        }

        $orderTransfer->create($args);

        $this->checkResponse($orderTransfer);

        return $orderTransfer;
    }

    /**
     * @param $query
     * @return OrderTransfer
     * @throws NotFoundException
     * @throws OwlPayException
     * @throws UnauthorizedException
     * @throws UnknownException
     */
    public function getOrdersTransfers($query = []): OrderTransfer
    {
        $orderTransfer = new OrderTransfer();

        if (!empty($this->secret)) {
            $orderTransfer->setSecret($this->secret);
        }

        $orderTransfer->all($query);

        $this->checkResponse($orderTransfer);

        return $orderTransfer;
    }

    /**
     * @param $order_transfer_uuid
     * @return OrderTransfer
     * @throws NotFoundException
     * @throws OwlPayException
     * @throws UnauthorizedException
     * @throws UnknownException
     */
    public function getOrdersTransferDetail($order_transfer_uuid): OrderTransfer
    {
        $orderTransfer = new OrderTransfer();

        if (!empty($this->secret)) {
            $orderTransfer->setSecret($this->secret);
        }

        $orderTransfer->detail($order_transfer_uuid);

        $this->checkResponse($orderTransfer);

        return $orderTransfer;
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

    /**
     * @param $input
     * @param string $separator
     * @return string|string[]
     */
    private function camelize($input, $separator = '_')
    {
        return str_replace($separator, '', ucwords($input, $separator));
    }
}
