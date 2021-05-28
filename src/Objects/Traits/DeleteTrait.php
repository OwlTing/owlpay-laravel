<?php
namespace Owlting\OwlPay\Objects\Traits;

trait DeleteTrait
{
    /**
     * @param mixed ...$args
     * @return DeleteTrait
     */
    public function delete(...$args)
    {
        $url = self::getUrl(self::DELETE, $args);

        $response = $this->_client->delete($url, [
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
