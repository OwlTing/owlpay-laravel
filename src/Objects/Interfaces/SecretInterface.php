<?php


namespace Owlting\OwlPay\Objects\Interfaces;


interface SecretInterface
{
    public function setSecret(string $secret);
    public function getSecret(): string;
}
