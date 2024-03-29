<?php


namespace Owlting\OwlPay\Objects;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Validator;
use Owlting\OwlPay\Exceptions\MissingParameterException;
use Owlting\OwlPay\Exceptions\RouteNotFoundException;
use Owlting\OwlPay\Objects\Interfaces\CreateInterface;
use Owlting\OwlPay\Objects\Interfaces\DetailInterface;
use Owlting\OwlPay\Objects\Interfaces\ListInterface;
use Owlting\OwlPay\Objects\Interfaces\SecretInterface;
use Owlting\OwlPay\Objects\Traits\CreateTrait;
use Owlting\OwlPay\Objects\Traits\DetailTrait;
use Owlting\OwlPay\Objects\Traits\ListTrait;
use Owlting\OwlPay\Objects\Traits\SecretTrait;

class OrdersReconciliation extends BaseObject implements ListInterface, CreateInterface, DetailInterface, SecretInterface
{
    use ListTrait;
    use DetailTrait;
    use CreateTrait;
    use SecretTrait;

    const CONFIRM = 'confirm';

    protected static $url_map = [
        self::SHOW_LIST => '/api/v1/platform/tunnel/orders_reconciliation',
        self::CREATE => '/api/v1/platform/tunnel/orders_reconciliation',
        self::SHOW_DETAIL => '/api/v1/platform/tunnel/orders_reconciliation/%s',
    ];

    /**
     * OrderTransfer constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }
}
