<?php

namespace Owlting\OwlPay\Objects\Interfaces;

use Owlting\OwlPay\Exceptions\MissingParameterException;

interface BaseInterface
{
    /**
     * @param $event
     * @param $value
     * @return mixed
     * @throws MissingParameterException
     */
    public static function validate($event, $value);
}
