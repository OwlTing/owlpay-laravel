<?php

namespace Owlting\OwlPay\Facades;

use Illuminate\Support\Facades\Facade;
use Owlting\OwlPay\Objects\Vendor;
use Owlting\OwlPay\OwlPay as OwlPayItem;

/**
 * @method static \Owlting\OwlPay\Objects\Order createOrder($args = [])
 * @method static \Owlting\OwlPay\Objects\Order createOrders($orders = [])
 * @method static \Owlting\OwlPay\Objects\Order getOrders($query = [])
 * @method static \Owlting\OwlPay\Objects\Order getOrderDetail(string $order_uuid)
 * @method static \Owlting\OwlPay\Objects\Order cancelOrder($args = [])
 * @method static \Owlting\OwlPay\Objects\Order mapOrderData($args = [])
 *
 * @method static \Owlting\OwlPay\Objects\Vendor createVendor($args = [])
 * @method static \Owlting\OwlPay\Objects\Vendor updateVendor($vendor_uuid, $args = [])
 * @method static \Owlting\OwlPay\Objects\Vendor getVendors($query = [])
 * @method static \Owlting\OwlPay\Objects\Vendor getVendorDetail(string $vendor_uuid)
 * @method static \Owlting\OwlPay\Objects\Vendor deleteVendor(string $vendor_uuid)
 * @method static \Owlting\OwlPay\Objects\Vendor getVendorOrders(string $vendor_uuid, $query = [])
 *
 * @method static \Owlting\OwlPay\Objects\VendorInvite createVendorInvite($args = [])
 *
 * @method static \Owlting\OwlPay\Objects\OrdersReconciliation createOrdersReconciliation($args = [])
 * @method static \Owlting\OwlPay\Objects\OrdersReconciliation getOrdersReconciliations($query = [])
 * @method static \Owlting\OwlPay\Objects\OrdersReconciliation getOrdersReconciliationDetail(string $orders_reconciliation_uuid)
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
