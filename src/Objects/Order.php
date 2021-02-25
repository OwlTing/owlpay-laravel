<?php


namespace Owlting\OwlPay\Objects;


use Illuminate\Support\Facades\Validator;
use Owlting\OwlPay\Exceptions\MissingParameterException;
use Owlting\OwlPay\Objects\Interfaces\CancelInterface;
use Owlting\OwlPay\Objects\Interfaces\CreateInterface;
use Owlting\OwlPay\Objects\Interfaces\DetailInterface;
use Owlting\OwlPay\Objects\Interfaces\ListInterface;
use Owlting\OwlPay\Objects\Interfaces\SecretInterface;
use Owlting\OwlPay\Objects\Traits\CancelTrait;
use Owlting\OwlPay\Objects\Traits\CreateTrait;
use Owlting\OwlPay\Objects\Traits\DetailTrait;
use Owlting\OwlPay\Objects\Traits\ListTrait;
use Owlting\OwlPay\Objects\Traits\SecretTrait;

class Order extends BaseObject implements CreateInterface, DetailInterface, SecretInterface, CancelInterface, ListInterface
{
    use CreateTrait;
    use DetailTrait;
    use CancelTrait;
    use SecretTrait;
    use ListTrait;

    protected static $url_map = [
        self::SHOW_LIST => '/api/platform/tunnel/orders',
        self::CREATE => '/api/v1/platform/tunnel/orders',
        self::SHOW_DETAIL => '/api/v1/platform/tunnel/orders/%s',
        self::CANCEL => '/api/v1/platform/tunnel/orders/cancel'
    ];

    protected static $create_validator = [
        'order_serial' => 'required',
        'currency' => 'required',
        'total' => 'required',
        'description' => 'nullable',
        'is_force_create' => 'nullable|boolean',
        'order_created_at' => 'nullable',
        'meta_data' => 'nullable|array',

        'vendor.name' => 'nullable|string',
        'vendor.uuid' => 'nullable|string',
        'vendor.customer_vendor_uuid' => 'nullable|string',
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
        'order_tokens' => 'required_without:application_order_serials|array',
        'application_order_serials' => 'required_without:order_tokens|array'
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
     * @param $input
     * @return array|mixed
     * @throws MissingParameterException
     */
    public static function validate($event, $input): array
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
            default:
                $validates = [];
        }

        if (class_exists(Validator::class)) {
            $validator = Validator::make($input, $validates);
            if ($validator->fails()) {
                throw new MissingParameterException($validator->errors()->first());
            }
            return $input;
        } else {
            foreach ($validates as $key => $validate) {
                if (in_array('required', explode('|', $validate), true)) {
                    if (!in_array($key, $input, true)) {
                        throw new MissingParameterException("{$key} required");
                    }
                }
            }
            return $input;
        }
    }
}
