<?php

namespace Owlting\OwlPay\Objects\Traits;

Trait CancelTrait
{
    /**
     * @param $value
     */
    public function cancel($value)
    {
        $url = self::getUrl(self::CANCEL);

        $input = $this::validate(self::CANCEL, $value);

        $response = $this->_client->put($url, [
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
