<?php
namespace Owlting\OwlPay\Objects;

use Illuminate\Support\Facades\Validator;
use Owlting\OwlPay\Exceptions\MissingParameterException;
use Owlting\OwlPay\Objects\Interfaces\CreateInterface;
use Owlting\OwlPay\Objects\Interfaces\DetailInterface;
use Owlting\OwlPay\Objects\Interfaces\InviteInterface;
use Owlting\OwlPay\Objects\Interfaces\SecretInterface;
use Owlting\OwlPay\Objects\Traits\CreateTrait;
use Owlting\OwlPay\Objects\Traits\DetailTrait;
use Owlting\OwlPay\Objects\Traits\SecretTrait;

class Vendor extends BaseObject implements CreateInterface, DetailInterface, InviteInterface, SecretInterface
{
    use CreateTrait;
    use DetailTrait;
    use SecretTrait;

    const INVITE = 'invite';

    protected static $url_map = [
//        self::CREATE => '/api/platform/vendors',
//        self::SHOW_DETAIL => '/api/platform/vendors/{vendor_uuid}',
        self::INVITE => '/api/v1/platform/tunnel/vendor_invite',
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

    public function invite($email, $args = [])
    {
        $invite_input = array_merge($args, [
            'email' => $email
        ]);

        $url = self::getUrl(self::INVITE);

        $input = $this::validate(self::INVITE, $invite_input);

        $response = $this->_client->post($url, [
            'headers' => [
                'Authorization' => 'Bearer ' .
                property_exists(self::class, 'secret') ? $this->secret : config('owlpay.application_secret'),
            ],
            'form_params' => $input
        ]);

        $response_data = $this->_interpretResponse(
            $response->getBody()->getContents(),
            $response->getStatusCode(),
            $response->getHeaders()
        );

        $this->_lastResponse = $response_data;

        $this->_values = $this->_lastResponse['data'] ?? [];

        return $this;
    }
}
