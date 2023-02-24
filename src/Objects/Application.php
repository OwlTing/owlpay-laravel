<?php


namespace Owlting\OwlPay\Objects;


use Owlting\OwlPay\Objects\Interfaces\ListInterface;
use Owlting\OwlPay\Objects\Interfaces\SecretInterface;
use Owlting\OwlPay\Objects\Traits\CancelTrait;
use Owlting\OwlPay\Objects\Traits\CreateBatchTrait;
use Owlting\OwlPay\Objects\Traits\CreateTrait;
use Owlting\OwlPay\Objects\Traits\DetailTrait;
use Owlting\OwlPay\Objects\Traits\ListTrait;
use Owlting\OwlPay\Objects\Traits\SecretTrait;

class Application extends BaseObject implements SecretInterface
{
    protected $data = [];

    use SecretTrait;

    const SHOW_APPLICATION = 'show_application';

    protected static $url_map = [
        self::SHOW_APPLICATION => '/api/v1/platform/tunnel/application',
    ];

    /**
     * @param $vendor_uuid
     * @param array $query
     * @return $this
     * @throws MissingParameterException
     * @throws \Owlting\OwlPay\Exceptions\RouteNotFoundException
     */
    public function show(): Application
    {
        $url = self::getUrl(self::SHOW_APPLICATION);

        $response = $this->_client->get($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . (empty($this->secret) ? config('owlpay.application_secret') : $this->secret),
            ]
        ]);

        $this->_lastResponse = $this->_interpretResponse(
            $response->getBody()->getContents(),
            $response->getStatusCode(),
            $response->getHeaders()
        );

        $data = [
            'data' => $this->_lastResponse['data'] ?? [],
        ];

        $this->_values = $data;

        return $this;
    }
}
