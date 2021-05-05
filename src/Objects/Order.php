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

    protected static $create_validator = [
        'order_serial' => 'required',
        'currency' => 'required',
        'total' => 'required',
        'description' => 'nullable',
        'is_force_create' => 'nullable|boolean',
        'order_created_at' => 'nullable|date_format:Y-m-d\TH:i:sP',
        'allow_transfer_time_at' => 'nullable|date_format:Y-m-d\TH:i:sP',
        'meta_data' => 'nullable|array',

        'vendor.name' => 'nullable|string',
        'vendor.uuid' => 'nullable|string',
        'vendor.application_vendor_uuid' => 'nullable',
        'vendor.email' => 'nullable|email',
        'vendor.remit_info.country_iso' => 'nullable',
        'vendor.remit_info.type' => 'nullable',
        'vendor.remit_info.bank_name' => 'nullable',
        'vendor.remit_info.bank_subname' => 'nullable',
        'vendor.remit_info.bank_code' => 'nullable',
        'vendor.remit_info.bank_subcode' => 'nullable',
        'vendor.remit_info.bank_account' => 'nullable',
        'vendor.remit_info.bank_account_name' => 'nullable',
    ];

    protected static $list_validator = [
        'limit' => 'nullable',
        'page' => 'nullable',
        'order_by' => 'nullable',
        'sort_by' => 'nullable',
    ];

    protected static $cancel_validator = [
        'order_uuids' => 'required_without:application_order_serials|array',
        'application_order_serials' => 'required_without:order_uuids|array'
    ];

    protected static $create_batch_validator = [
        'orders.*.order_serial' => 'required',
        'orders.*.currency' => 'required',
        'orders.*.total' => 'required',
        'orders.*.is_force_create' => 'nullable|boolean',
        'orders.*.description' => 'nullable',
        'orders.*.meta_data' => 'nullable',
        'orders.*.order_created_at' => 'nullable|date_format:Y-m-d\TH:i:sP',
        'orders.*.allow_transfer_time_at' => 'nullable|date_format:Y-m-d\TH:i:sP',

        'orders.*.vendor' => 'array',
        'orders.*.vendor.name' => 'nullable',
        'orders.*.vendor.email' => 'nullable',
        "orders.*.vendor.remit_info.type" => 'nullable|in:basic',
        "orders.*.vendor.remit_info.country_iso" => "nullable",
        "orders.*.vendor.remit_info.bank_account_name" => "nullable",
        "orders.*.vendor.remit_info.bank_code" => "nullable",
        "orders.*.vendor.remit_info.bank_account" => "nullable",
        "orders.*.vendor.remit_info.bank_subname" => "nullable",
        "orders.*.vendor.remit_info.bank_name" => "nullable",
        "orders.*.vendor.remit_info.bank_subcode" => "nullable",
    ];


    /**
     * Order constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * @param $event
     * @param $value
     * @return array|mixed
     * @throws MissingParameterException
     */
    public static function validate($event, $value): array
    {
        switch ($event) {
            case self::CREATE:
                $validates = self::$create_validator;
                break;
            case self::SHOW_LIST:
                $validates = self::$list_validator;
                break;
            case self::CANCEL:
                $validates = self::$cancel_validator;
                break;
            case self::CREATE_BATCH:
                $validates = self::$create_batch_validator;
                break;
            default:
                $validates = [];
        }

        if (class_exists(Validator::class)) {
            $validator = Validator::make($value, $validates);
            if ($validator->fails()) {
                throw new MissingParameterException($validator->errors()->first());
            }
            return $value;
        } else {
            foreach ($validates as $key => $validate) {
                if (in_array('required', explode('|', $validate), true)) {
                    if (!in_array($key, $value, true)) {
                        throw new MissingParameterException("{$key} required");
                    }
                }
            }
            return $value;
        }
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
