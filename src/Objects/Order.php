<?php


namespace Owlting\OwlPay\Objects;


use Illuminate\Support\Facades\Validator;
use Owlting\OwlPay\Exceptions\MissingParameterException;
use Owlting\OwlPay\Objects\Interfaces\CreateInterface;
use Owlting\OwlPay\Objects\Traits\CreateTrait;
use Owlting\OwlPay\Objects\Traits\DetailTrait;
use Owlting\OwlPay\Objects\Traits\ListTrait;

class Order extends BaseObject implements CreateInterface
{
    use CreateTrait;
    use ListTrait;
    use DetailTrait;

    const VENDOR_REQUEST_PAY = 'vendor_request_pay';

    protected static $url_map = [
        self::CREATE => '/api/platform/orders',
        self::SHOW_DETAIL => '/api/platform/orders/{order_token}',
        self::VENDOR_REQUEST_PAY => '/api/platform/orders/{order_token}/vendor_request_pay',
    ];

    protected static $create_validator = [
        'order_serial' => 'required',
        'currency' => 'required',
        'total' => 'required',
        'description' => 'nullable',
        'is_force_create' => 'nullable|boolean',
        'vendor_uuid' => 'nullable|string',
        'meta_data' => 'nullable|array',
    ];

    protected static $list_validator = [
        'limit' => 'nullable',
        'page' => 'nullable',
        'order_by' => 'nullable',
        'sort_by' => 'nullable',
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
    public static function validate($event, $input)
    {
        switch ($event) {
            case self::CREATE:
                $validates = self::$create_validator;
                break;
            case self::SHOW_LIST:
                $validates = self::$list_validator;
                break;
            default:
                $validates = [];
        }

        if (class_exists(Validator::class)) {
            $validator = Validator::make($input, $validates);
            if ($validator->fails()) {
                throw new MissingParameterException();
            }
            return $validator->validated();
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
