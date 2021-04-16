<?php
namespace Owlting\OwlPay\Objects\Traits;


trait UpdateTrait
{

    /**
     * @param $value
     * @param mixed ...$args
     * @return UpdateTrait
     */
    public function update($value, ...$args)
    {
        $url = self::getUrl(self::UPDATE, $args);

        $input = $this::validate(self::UPDATE, $value);

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