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

    /**
     * Vendor constructor.
     */
    public function __construct()
    {
        parent::__construct();
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
        $input = array_merge($args, [
            'email' => $email
        ]);

        $url = self::getUrl(self::INVITE);

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

        $this->_client = new Client();

        $response = $this->_client->get($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . (empty($this->secret) ? config('owlpay.application_secret') : $this->secret),
            ],
            'query' => $query,
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
