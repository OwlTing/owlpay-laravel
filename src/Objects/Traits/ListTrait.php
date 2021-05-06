<?php

namespace Owlting\OwlPay\Objects\Traits;

use GuzzleHttp\Client;
use Owlting\OwlPay\Exceptions\InvalidRequestException;
use Owlting\OwlPay\Exceptions\MissingParameterException;

Trait ListTrait
{
    /**
     * @param array $query
     * @return ListTrait
     */
    public function all(array $query)
    {
        $url = self::getUrl(self::SHOW_LIST);

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
