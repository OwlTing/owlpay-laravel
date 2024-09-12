<?php

namespace Owlting\OwlPay\Objects\Traits;

use GuzzleHttp\Client;
use Owlting\OwlPay\Exceptions\InvalidRequestException;
use Owlting\OwlPay\Exceptions\MissingParameterException;

Trait DetailTrait
{
    /**
     * @param mixed ...$args
     * @return DetailTrait
     */
    public function detail(...$args)
    {
        $url = self::getUrl(self::SHOW_DETAIL, $args);

        $response = $this->_client->get($url, [
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
