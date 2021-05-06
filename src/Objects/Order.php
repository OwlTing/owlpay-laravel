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

class Order extends BaseObject implements CreateBatchInterface, CreateInterface, DetailInterface, SecretInterface, CancelInterface, ListInterface
{
    protected $data = [];

    use CreateBatchTrait;
    use CreateTrait;
    use DetailTrait;
    use CancelTrait;
    use SecretTrait;
    use ListTrait;

    protected static $url_map = [
        self::SHOW_LIST => '/api/platform/tunnel/orders',
        self::CREATE => '/api/v1/platform/tunnel/orders',
        self::SHOW_DETAIL => '/api/v1/platform/tunnel/orders/%s',
        self::CANCEL => '/api/v1/platform/tunnel/orders/cancel',
        self::CREATE_BATCH => '/api/v1/platform/tunnel/orders/batch'
    ];

    /**
     * Order constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param array $date
     * @return Order
     */
    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param array $date
     * @return Order
     */
    public function getData()
    {
        return $this->data;
    }
}
