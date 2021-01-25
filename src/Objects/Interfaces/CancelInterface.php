<?php

namespace Owlting\OwlPay\Objects\Interfaces;

interface CancelInterface
{
    public function cancel($tokens);
}
