<?php
namespace Owlting\OwlPay\Objects;

use Illuminate\Support\Facades\Validator;
use Owlting\OwlPay\Exceptions\MissingParameterException;
use Owlting\OwlPay\Objects\Interfaces\CreateInterface;
use Owlting\OwlPay\Objects\Interfaces\DetailInterface;
use Owlting\OwlPay\Objects\Interfaces\InviteInterface;
use Owlting\OwlPay\Objects\Traits\CreateTrait;
use Owlting\OwlPay\Objects\Traits\DetailTrait;

class Vendor extends BaseObject implements CreateInterface, DetailInterface, InviteInterface
{
    use CreateTrait;
    use DetailTrait;

    const INVITE = 'invite';

    protected static $url_map = [
        self::CREATE => '/api/platform/vendors',
        self::SHOW_DETAIL => '/api/platform/vendors/{vendor_uuid}',
        self::INVITE => '/api/platform/vendor_invite',
    ];

    protected static $create_validator = [
//        'order_serial' => 'required',
//        'currency' => 'required',
//        'total' => 'required',
//        'description' => 'nullable',
//        'is_force_create' => 'nullable|boolean',
//        'vendor_uuid' => 'nullable|string',
        'meta_data' => 'nullable|array',
    ];

    protected static $list_validator = [
        'limit' => 'nullable',
        'page' => 'nullable',
        'order_by' => 'nullable',
        'sort_by' => 'nullable',
    ];

    protected static $invite_validator = [
        'is_owlpay_send_email' => 'boolean',
        'email' => 'email',
        'vendor_uuid' => '',
        'meta_data' => 'nullable|array',
    ];

    /**
     * Vendor constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public static function validate($event, $input)
    {
        switch ($event) {
            case self::CREATE:
                $validates = self::$create_validator;
                break;
            case self::SHOW_LIST:
                $validates = self::$list_validator;
                break;
            case self::INVITE:
                $validates = self::$invite_validator;
                break;
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

    public function invite($email, $args = [], $meta_data = [])
    {
        // TODO: Implement invite() method.
    }
}