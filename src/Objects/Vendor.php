<?php
namespace Owlting\OwlPay\Objects;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Validator;
use Owlting\OwlPay\Exceptions\MissingParameterException;
use Owlting\OwlPay\Objects\Interfaces\CreateInterface;
use Owlting\OwlPay\Objects\Interfaces\DetailInterface;
use Owlting\OwlPay\Objects\Interfaces\InviteInterface;
use Owlting\OwlPay\Objects\Interfaces\ListInterface;
use Owlting\OwlPay\Objects\Interfaces\SecretInterface;
use Owlting\OwlPay\Objects\Interfaces\UpdateInterface;
use Owlting\OwlPay\Objects\Traits\CreateTrait;
use Owlting\OwlPay\Objects\Traits\DetailTrait;
use Owlting\OwlPay\Objects\Traits\ListTrait;
use Owlting\OwlPay\Objects\Traits\SecretTrait;
use Owlting\OwlPay\Objects\Traits\UpdateTrait;

class Vendor extends BaseObject implements CreateInterface, DetailInterface, InviteInterface, SecretInterface, ListInterface, UpdateInterface
{
    use CreateTrait;
    use UpdateTrait;
    use DetailTrait;
    use SecretTrait;
    use ListTrait;

    const INVITE = 'invite';
    const SHOW_VENDOR_ORDER_LIST = 'vendor_orders';

    protected static $url_map = [
        self::CREATE => '/api/platform/tunnel/vendors',
        self::UPDATE => '/api/platform/tunnel/vendors/%s',
        self::SHOW_DETAIL => '/api/v1/platform/tunnel/vendors/%s',
        self::SHOW_LIST => '/api/v1/platform/tunnel/vendors',
        self::SHOW_VENDOR_ORDER_LIST => '/api/v1/platform/tunnel/vendors/%s/orders',
        self::INVITE => '/api/v1/platform/tunnel/vendor_invite',
    ];

    protected static $create_validator = [
        'name' => 'nullable|string',
        'uuid' => 'nullable|string',
        'customer_vendor_uuid' => 'nullable|string',
        'email' => 'nullable|email',
        'description' => 'nullable',
        'remit_info.country_iso' => 'nullable',
        'remit_info.type' => 'nullable',
        'remit_info.bank_name' => 'nullable',
        'remit_info.bank_subname' => 'nullable',
        'remit_info.bank_code' => 'nullable',
        'remit_info.bank_subcode' => 'nullable',
        'remit_info.bank_account' => 'nullable',
        'remit_info.bank_account_name' => 'nullable',
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

    /**
     * @param $event
     * @param $input
     * @return mixed
     * @throws MissingParameterException
     */
    public static function validate($event, $input)
    {
        switch ($event) {
            case self::CREATE:
            case self::UPDATE:
                $validates = self::$create_validator;
                break;
            case self::SHOW_VENDOR_ORDER_LIST:
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

    /**
     * @param $email
     * @param array $args
     * @return $this
     * @throws MissingParameterException
     * @throws \Owlting\OwlPay\Exceptions\RouteNotFoundException
     */
    public function invite($email, $args = []): Vendor
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

    /**
     * @param $vendor_uuid
     * @param array $query
     * @return $this
     * @throws MissingParameterException
     * @throws \Owlting\OwlPay\Exceptions\RouteNotFoundException
     */
    public function vendor_orders($vendor_uuid, array $query): Vendor
    {
        $args = [$vendor_uuid];

        $url = self::getUrl(self::SHOW_VENDOR_ORDER_LIST, $args);

        $validated = $this::validate(self::SHOW_VENDOR_ORDER_LIST, $query);

        $this->_client = new Client();

        $response = $this->_client->get($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . (empty($this->secret) ? config('owlpay.application_secret') : $this->secret),
            ],
            'query' => $validated,
        ]);

        $this->_lastResponse = $this->_interpretResponse(
            $response->getBody()->getContents(),
            $response->getStatusCode(),
            $response->getHeaders()
        );

        $data = [
            'data' => $this->_lastResponse['data'] ?? [],
            'pagination' => $this->_lastResponse['pagination'] ?? [],
        ];

        $this->_values = $data;

        return $this;
    }
}
