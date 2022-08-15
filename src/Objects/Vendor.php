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
use Owlting\OwlPay\Objects\Interfaces\DeleteInterface;
use Owlting\OwlPay\Objects\Traits\CreateTrait;
use Owlting\OwlPay\Objects\Traits\DetailTrait;
use Owlting\OwlPay\Objects\Traits\ListTrait;
use Owlting\OwlPay\Objects\Traits\SecretTrait;
use Owlting\OwlPay\Objects\Traits\UpdateTrait;
use Owlting\OwlPay\Objects\Traits\DeleteTrait;

class Vendor extends BaseObject implements
    CreateInterface,
    DetailInterface,
    InviteInterface,
    SecretInterface,
    ListInterface,
    UpdateInterface,
    DeleteInterface
{
    use CreateTrait;
    use UpdateTrait;
    use DetailTrait;
    use SecretTrait;
    use ListTrait;
    use DeleteTrait;

    const INVITE = 'invite';
    const APPLY_VENDOR_REMIT_INFO = 'apply_vendor_remit_info';
    const SHOW_VENDOR_ORDER_LIST = 'vendor_orders';
    const SHOW_VENDOR_REMIT_INFO_LIST = 'vendor_remit_info';

    protected static $url_map = [
        self::CREATE => '/api/platform/tunnel/vendors',
        self::UPDATE => '/api/platform/tunnel/vendors/%s',
        self::SHOW_DETAIL => '/api/v1/platform/tunnel/vendors/%s',
        self::SHOW_LIST => '/api/v1/platform/tunnel/vendors',
        self::SHOW_VENDOR_ORDER_LIST => '/api/v1/platform/tunnel/vendors/%s/orders',
        self::SHOW_VENDOR_REMIT_INFO_LIST => '/api/v1/platform/tunnel/vendors/%s/remit_info',
        self::APPLY_VENDOR_REMIT_INFO => '/api/v1/platform/tunnel/vendors/%s/remit_info/apply',
        self::DELETE => '/api/platform/tunnel/vendors/%s',
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
    public function orders($vendor_uuid, array $query): Vendor
    {
        $args = [$vendor_uuid];

        $url = self::getUrl(self::SHOW_VENDOR_ORDER_LIST, $args);

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

    /**
     * @param $vendor_uuid
     * @param array $query
     * @return $this
     * @throws MissingParameterException
     * @throws \Owlting\OwlPay\Exceptions\RouteNotFoundException
     */
    public function remit_info($vendor_uuid, array $query): Vendor
    {
        $args = [$vendor_uuid];

        $url = self::getUrl(self::SHOW_VENDOR_REMIT_INFO_LIST, $args);

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

    /**
     * @param $vendor_uuid
     * @param array $args
     * @return $this
     * @throws MissingParameterException
     * @throws \Owlting\OwlPay\Exceptions\RouteNotFoundException
     */
    public function applyRemitInfo($vendor_uuid, $args = []): Vendor
    {
        $url = self::getUrl(self::APPLY_VENDOR_REMIT_INFO, [$vendor_uuid]);

        $response = $this->_client->post($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . (empty($this->secret) ? config('owlpay.application_secret') : $this->secret),
            ],
            'form_params' => $args
        ]);
        $this->_lastResponse = $this->_interpretResponse(
            $response->getBody()->getContents(),
            $response->getStatusCode(),
            $response->getHeaders()
        );
        $this->_values = $this->_lastResponse['data'] ?? [];

        return $this;
    }
}
