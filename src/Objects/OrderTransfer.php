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

class OrderTransfer extends BaseObject implements ListInterface, CreateInterface, DetailInterface, SecretInterface
{
    use ListTrait;
    use DetailTrait;
    use CreateTrait;
    use SecretTrait;

    const CONFIRM = 'confirm';
    const SHOW_TRANSFER_ORDER_LIST = 'orders_transfer_orders';

    protected static $url_map = [
        self::SHOW_LIST => '/api/platform/tunnel/orders_transfer',
        self::CREATE => '/api/v1/platform/tunnel/orders_transfer',
        self::SHOW_DETAIL => '/api/v1/platform/tunnel/orders_transfer/%s',
        self::SHOW_TRANSFER_ORDER_LIST => '/api/v1/platform/tunnel/orders_transfer/%s/orders',
        self::CONFIRM => '/api/v1/platform/tunnel/orders_transfer/%s/confirm',
        self::CANCEL => '/api/v1/platform/tunnel/orders_transfer/%s/cancel'
    ];

    protected static $create_validator = [
        'vendor_uuid' => 'nullable',
        'vendor_email' => 'nullable',
        'order_serial' => 'nullable',
        'order_serials' => 'nullable|array',
        'order_uuid' => 'nullable',
        'order_uuids' => 'nullable|array',
        'order_status' => 'nullable',
        'application_vendor_uuid' => 'nullable',
        'during_order_created_at' => 'nullable',
        'description' => 'nullable',
        'meta_data' => 'nullable|array',
    ];

    protected static $list_validator = [
        'limit' => 'nullable',
        'page' => 'nullable',
        'order_by' => 'nullable',
        'sort_by' => 'nullable',
    ];

    /**
     * OrderTransfer constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public static function validate($event, $value)
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
     * @param $order_transfer_uuid
     * @param array $query
     * @return OrderTransfer
     * @throws MissingParameterException
     * @throws RouteNotFoundException
     */
    public function transfer_orders($order_transfer_uuid, array $query): OrderTransfer
    {
        $args = [$order_transfer_uuid];

        $url = self::getUrl(self::SHOW_TRANSFER_ORDER_LIST, $args);

        $validated = $this::validate(self::SHOW_TRANSFER_ORDER_LIST, $query);

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

    /**
     * @param $args
     * @return $this
     * @throws RouteNotFoundException
     */
    public function confirm(...$args): OrderTransfer
    {
        $url = self::getUrl(self::CONFIRM, $args);

        $response = $this->_client->put($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . (empty($this->secret) ? config('owlpay.application_secret') : $this->secret),
            ]
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
     * @param $args
     * @return $this
     * @throws RouteNotFoundException
     */
    public function cancel(...$args): OrderTransfer
    {
        $url = self::getUrl(self::CANCEL, $args);

        $response = $this->_client->put($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . (empty($this->secret) ? config('owlpay.application_secret') : $this->secret),
            ]
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