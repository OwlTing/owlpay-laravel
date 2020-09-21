<?php

namespace Owlting\OwlPay\Objects\Traits;

use GuzzleHttp\Client;
use Owlting\OwlPay\Exceptions\InvalidRequestException;
use Owlting\OwlPay\Exceptions\MissingParameterException;

Trait DetailTrait
{
    /**
     * @param array $query
     * @return
     * @throws InvalidRequestException
     */
    public function detail($order_token)
    {
        $url = self::getUrl(self::SHOW_DETAIL, compact('order_token'));

        $response = $this->_client->get($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . config('owlpay.application_secret'),
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
