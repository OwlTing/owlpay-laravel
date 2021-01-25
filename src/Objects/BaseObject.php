<?php

namespace Owlting\OwlPay\Objects;

use GuzzleHttp\Client;
use Owlting\OwlPay\Exceptions\RouteNotFoundException;
use Owlting\OwlPay\Objects\Interfaces\BaseInterface;


abstract class BaseObject implements \ArrayAccess, \Countable, BaseInterface, \JsonSerializable
{
    const CREATE = 'create';
    const CANCEL = 'cancel';
    const SHOW_LIST = 'list';
    const SHOW_DETAIL = 'detail';
    const DELETE = 'delete';

    protected static $url_map = [];

    /**
     * @var \GuzzleHttp\Client
     */
    protected $_client;

    /**
     * @var array
     */
    protected $_values;

    /**
     * @var array
     */
    protected $_lastResponse;

    public function __construct()
    {
        $this->_values = [];
        $this->_lastResponse = [];
        /** @var \GuzzleHttp\Client _client */
        $this->_client = app(Client::class);
    }

    public function offsetExists($offset)
    {
        return \array_key_exists($offset, $this->_values);
    }

    public function offsetGet($offset)
    {
        return \array_key_exists($offset, $this->_values) ? $this->_values[$offset] : null;
    }

    public function offsetSet($offset, $value)
    {
        $this->_values[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->_values[$offset]);
    }

    public function count()
    {
        return \count($this->_values) ?: 0;
    }

    public function getLastResponse()
    {
        return $this->_lastResponse;
    }

    /**
     * @param $rbody
     * @param $rcode
     * @param $rheaders
     * @return mixed|null
     * @throws \Exception
     */
    protected function _interpretResponse($rbody, $rcode, $rheaders)
    {
        $resp = \json_decode($rbody, true);
        $jsonError = \json_last_error();
        if (null === $resp && \JSON_ERROR_NONE !== $jsonError) {
            $msg = "Invalid response body from API: {$rbody} "
                . "(HTTP response code was {$rcode}, json_last_error() was {$jsonError})";

            throw new \Exception($msg, $rcode);
        }

        return $resp;
    }

    public function jsonSerialize()
    {
        return $this->_values;
    }

    /**
     * @param $event
     * @param array $routes
     * @return string
     * @throws RouteNotFoundException
     */
    protected function getUrl($event, $routes = [])
    {
        /** @var array $url_map */
        $url = $this::$url_map[$event];

        if (empty($url)) {
            throw new RouteNotFoundException();
        }

        foreach ($routes as $key => $route) {
            $url = str_replace('{' . $key . '}', $route, $url);
        }

        return config('owlpay.api_url') . $url;
    }
}
