<?php
namespace Owlting\OwlPay\Objects\Traits;


trait AssociateUpdateTrait
{

    /**
     * @param $input
     * @param mixed ...$args
     * @return AssociateUpdateTrait
     */
    public function associateUpdate($input, ...$args)
    {
        $url = self::getUrl(self::ASSOCIATE_UPDATE, $args);

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
