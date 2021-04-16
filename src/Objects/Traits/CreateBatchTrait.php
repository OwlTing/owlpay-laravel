<?php
namespace Owlting\OwlPay\Objects\Traits;

trait CreateBatchTrait
{

    /**
     * @param $values
     * @return CreateBatchTrait
     */
    public function createBatch($values)
    {
        $url = self::getUrl(self::CREATE_BATCH);

        $orders = array_map(function ($value) {
            return $value->getData();
        }, $values);

        $input = $this::validate(self::CREATE_BATCH, compact('orders'));

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