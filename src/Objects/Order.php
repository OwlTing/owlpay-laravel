<?php


namespace Owlting\OwlPay\Objects;


use Illuminate\Support\Facades\Validator;
use Owlting\OwlPay\Exceptions\MissingParameterException;
use Owlting\OwlPay\Objects\Interfaces\CancelInterface;
use Owlting\OwlPay\Objects\Interfaces\CreateBatchInterface;
use Owlting\OwlPay\Objects\Interfaces\CreateInterface;
use Owlting\OwlPay\Objects\Interfaces\DetailInterface;
use Owlting\OwlPay\Objects\Interfaces\ListInterface;
use Owlting\OwlPay\Objects\Interfaces\SecretInterface;
use Owlting\OwlPay\Objects\Traits\CancelTrait;
use Owlting\OwlPay\Objects\Traits\CreateBatchTrait;
use Owlting\OwlPay\Objects\Traits\CreateTrait;
use Owlting\OwlPay\Objects\Traits\DetailTrait;
use Owlting\OwlPay\Objects\Traits\ListTrait;
use Owlting\OwlPay\Objects\Traits\SecretTrait;
use Owlting\OwlPay\Objects\Traits\UpdateTrait;

class Order extends BaseObject implements CreateBatchInterface, CreateInterface, DetailInterface, SecretInterface, CancelInterface, ListInterface
{
    protected $data = [];

    use CreateBatchTrait;
    use CreateTrait;
    use DetailTrait;
    use CancelTrait;
    use SecretTrait;
    use ListTrait;
    use UpdateTrait;

    protected static $url_map = [
        self::SHOW_LIST => '/api/v1/platform/tunnel/orders',
        self::CREATE => '/api/v1/platform/tunnel/orders',
        self::SHOW_DETAIL => '/api/v1/platform/tunnel/orders/%s',
        self::CANCEL => '/api/v1/platform/tunnel/orders/cancel',
        self::CREATE_BATCH => '/api/v1/platform/tunnel/orders/batch',
        self::UPDATE => '/api/v1/platform/tunnel/orders/%s',
    ];

    /**
     * Order constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param  array  $data
     *
     * @return Order
     */
    public function setOrderData(array $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return array
     */
    public function getOrderData()
    {
        return $this->data;
    }
}
