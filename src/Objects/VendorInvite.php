<?php


namespace Owlting\OwlPay\Objects;


use Illuminate\Support\Facades\Validator;
use Owlting\OwlPay\Exceptions\MissingParameterException;
use Owlting\OwlPay\Objects\Interfaces\CreateInterface;
use Owlting\OwlPay\Objects\Interfaces\DetailInterface;
use Owlting\OwlPay\Objects\Interfaces\SecretInterface;
use Owlting\OwlPay\Objects\Traits\CreateTrait;
use Owlting\OwlPay\Objects\Traits\DetailTrait;
use Owlting\OwlPay\Objects\Traits\SecretTrait;

class VendorInvite extends BaseObject implements CreateInterface, DetailInterface, SecretInterface
{
    use CreateTrait;
    use DetailTrait;
    use SecretTrait;

    protected static $url_map = [
        self::CREATE => '/api/platform/vendor_invite',
//        self::SHOW_DETAIL => '/api/platform/orders/{order_token}',
    ];

    protected static $create_validator = [
        'is_owlpay_send_email' => 'boolean',
        'email' => 'nullable|email',
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
        'meta_data' => 'nullable|array',
    ];

    protected static $list_validator = [
        'limit' => 'nullable',
        'page' => 'nullable',
        'order_by' => 'nullable',
        'sort_by' => 'nullable',
    ];

    /**
     * VendorInvite constructor.
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
    public static function validate($event, $input)
    {
        switch ($event) {
            case self::CREATE:
                $validates = self::$create_validator;
                break;
//            case self::SHOW_LIST:
//                $validates = self::$list_validator;
//                break;
            default:
                $validates = [];
        }

        if (class_exists(Validator::class)) {
            $validator = Validator::make($input, $validates);
            if ($validator->fails()) {
                throw new MissingParameterException();
            }
            return $input;
        } else {
            foreach ($validates as $key => $validate) {
                if (in_array('required', explode('|', $validate), true)) {
                    if (!in_array($key, $input, true)) {
                        throw new MissingParameterException();
                    }
                }
            }
        }
    }
}
