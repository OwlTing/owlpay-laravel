<?php

namespace Owlting\OwlPay\Objects\Traits;

use Owlting\OwlPay\Exceptions\InvalidRequestException;
use Owlting\OwlPay\Exceptions\MissingParameterException;

trait CreateTrait
{

    /**
     * @param $value
     * @return CreateTrait
     */
    public function create($value)
    {
        $url = self::getUrl(self::CREATE);

        $input = $this::validate(self::CREATE, $value);

        $response = $this->_client->post($url, [
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
