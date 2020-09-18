<?php

namespace Owlting\OwlPay\Facades;

use Illuminate\Support\Facades\Facade;

class OwlPay extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'OwlPay';
    }
}
