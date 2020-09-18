<?php

namespace Owlting\OwlPay\Objects\Traits;

use GuzzleHttp\Client;
use Owlting\OwlPay\Exceptions\InvalidRequestException;
use Owlting\OwlPay\Exceptions\MissingParameterException;

trait CreateTrait
{

    /**
     * @param $order
     * @throws MissingParameterException
     * @throws InvalidRequestException
     */
    public function create($order)
    {
        $url = self::getUrl(self::CREATE);

        $input = $this::validate(self::CREATE, $order);

        $response = $this->_client->post($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . config('owlpay.application_secret'),
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

        return $this->_lastResponse;
    }
}
