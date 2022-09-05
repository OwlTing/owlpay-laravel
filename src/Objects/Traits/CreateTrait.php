<?php

namespace Owlting\OwlPay\Objects\Traits;

use Owlting\OwlPay\Exceptions\InvalidRequestException;
use Owlting\OwlPay\Exceptions\MissingParameterException;
use Owlting\OwlPay\Objects\Order;

trait CreateTrait
{

    /**
     * @param $input
     * @return CreateTrait
     */
    public function create($input)
    {
        $url = self::getUrl(self::CREATE);

        $response = $this->_client->post($url, [
            'version' => 1.0,
            'headers' => [
                'Authorization' => 'Bearer ' . (empty($this->secret) ? config('owlpay.application_secret') : $this->secret),
            ],
            'json' => $input
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
