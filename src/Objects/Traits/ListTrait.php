<?php

namespace Owlting\OwlPay\Objects\Traits;

use GuzzleHttp\Client;
use Owlting\OwlPay\Exceptions\InvalidRequestException;
use Owlting\OwlPay\Exceptions\MissingParameterException;

Trait ListTrait
{
    /**
     * @param array $query
     * @return
     * @throws InvalidRequestException
     */
    public function all($query)
    {
        $url = self::getUrl(self::SHOW_LIST);

        $validated = $this::validate(self::SHOW_LIST, $query);

        $this->_client = new Client();

        $response = $this->_client->get($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . config('owlpay.application_secret'),
            ],
            'query' => $validated,
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
