<?php

namespace Owlting\OwlPay;

use Owlting\OwlPay\Exceptions\NotFoundException;
use Owlting\OwlPay\Exceptions\OwlPayException;
use Owlting\OwlPay\Exceptions\UnauthorizedException;
use Owlting\OwlPay\Exceptions\UnknownException;
use Owlting\OwlPay\Exceptions\ClassNotFoundException;
use Owlting\OwlPay\Objects\Application;
use Owlting\OwlPay\Objects\BaseObject;
use Owlting\OwlPay\Objects\Interfaces\SecretInterface;
use Owlting\OwlPay\Objects\Order;
use Owlting\OwlPay\Objects\OrdersReconciliation;
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
     * @return Application
     * @throws NotFoundException
     * @throws OwlPayException
     * @throws UnauthorizedException
     * @throws UnknownException
     */
    public function getApplication()
    {
        $application = new Application();

        if (!empty($this->secret)) {
            $application->setSecret($this->secret);
        }

        $application->show();

        $this->checkResponse($application);

        return $application;
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
     * @param $order_uuid
     * @param $args
     *
     * @return Order
     * @throws NotFoundException
     * @throws OwlPayException
     * @throws UnauthorizedException
     * @throws UnknownException
     */
    public function updateOrder($order_uuid, $args = []): Order
    {
        $order = new Order();

        if (!empty($this->secret)) {
            $order->setSecret($this->secret);
        }

        if (false === strpos($order_uuid, 'ord_')) {
            throw new OwlPayException('Order prefix must be ord_');
        }

        $order->update($args, $order_uuid);

        $this->checkResponse($order);

        return $order;
    }

    /**
     * @param $order_uuid
     * @param $args
     *
     * @return Order
     * @throws NotFoundException
     * @throws OwlPayException
     * @throws UnauthorizedException
     * @throws UnknownException
     */
    public function associateUpdateOrder($order_uuid, $args = []): Order
    {
        $order = new Order();

        if (!empty($this->secret)) {
            $order->setSecret($this->secret);
        }

        if (false === strpos($order_uuid, 'ord_')) {
            throw new OwlPayException('Order prefix must be ord_');
        }

        $order->associateUpdate($args, $order_uuid);

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

        $vendor->orders($vendor_uuid, $query);

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
     * Apply vendor remit info
     * @param $vendor_uuid
     *
     * You can find fields by your vendor country and applicate type on OwlPay AML dynamic fields tools
     * OwlPay AML dynamic fields tools: https://owlting.github.io/owlting-aml-schema-tool/
     *
     * @param string $vendor_uuid
     * @param array $args
     * @return Vendor
     */
    public function applyVendorRemitInfo($vendor_uuid, $args = [])
    {
        $vendor = new Vendor();

        if (!empty($this->secret)) {
            $vendor->setSecret($this->secret);
        }

        $vendor->applyRemitInfo($vendor_uuid, $args);

        $this->checkResponse($vendor);

        return $vendor;
    }

    /**
     * Get vendor remit info
     *
     * @param string $vendor_uuid
     * @param array $query
     * @return Vendor
     */
    public function getVendorRemitInfo($vendor_uuid, $query = [])
    {
        $vendor = new Vendor();

        if (!empty($this->secret)) {
            $vendor->setSecret($this->secret);
        }

        $vendor->remit_info($vendor_uuid, $query);

        $this->checkResponse($vendor);

        return $vendor;
    }

    /**
     * @param $args
     * @return OrdersReconciliation
     * @throws NotFoundException
     * @throws OwlPayException
     * @throws UnauthorizedException
     * @throws UnknownException
     */
    public function createOrdersReconciliation($args = []): OrdersReconciliation
    {
        $ordersReconciliation = new OrdersReconciliation();

        if (!empty($this->secret)) {
            $ordersReconciliation->setSecret($this->secret);
        }

        $ordersReconciliation->create($args);

        $this->checkResponse($ordersReconciliation);

        return $ordersReconciliation;
    }

    /**
     * @param $query
     * @return OrdersReconciliation
     * @throws NotFoundException
     * @throws OwlPayException
     * @throws UnauthorizedException
     * @throws UnknownException
     */
    public function getOrdersReconciliations($query = []): OrdersReconciliation
    {
        $ordersReconciliation = new OrdersReconciliation();

        if (!empty($this->secret)) {
            $ordersReconciliation->setSecret($this->secret);
        }

        $ordersReconciliation->all($query);

        $this->checkResponse($ordersReconciliation);

        return $ordersReconciliation;
    }

    /**
     * @param $orders_reconciliation_uuid
     * @return OrdersReconciliation
     * @throws NotFoundException
     * @throws OwlPayException
     * @throws UnauthorizedException
     * @throws UnknownException
     */
    public function getOrdersReconciliationDetail($orders_reconciliation_uuid): OrdersReconciliation
    {
        $ordersReconciliation = new OrdersReconciliation();

        if (!empty($this->secret)) {
            $ordersReconciliation->setSecret($this->secret);
        }

        $ordersReconciliation->detail($orders_reconciliation_uuid);

        $this->checkResponse($ordersReconciliation);

        return $ordersReconciliation;
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
