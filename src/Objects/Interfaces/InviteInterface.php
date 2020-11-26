<?php
namespace Owlting\OwlPay\Objects\Interfaces;

interface InviteInterface
{
    public function invite($email, $args = []);
}