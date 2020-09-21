<?php

namespace Owlting\OwlPay\Exceptions;

use Throwable;

class OwlPayException extends \Exception
{
    protected $response;

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function setResponse($response)
    {
        $this->response = $response;
    }

    public function getResponse()
    {
        return $this->response;
    }
}
