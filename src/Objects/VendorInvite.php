<?php


namespace Owlting\OwlPay\Objects;


use Illuminate\Support\Facades\Validator;
use Owlting\OwlPay\Exceptions\MissingParameterException;
use Owlting\OwlPay\Objects\Interfaces\CreateInterface;
use Owlting\OwlPay\Objects\Interfaces\DetailInterface;
use Owlting\OwlPay\Objects\Interfaces\SecretInterface;
use Owlting\OwlPay\Objects\Traits\CreateTrait;
use Owlting\OwlPay\Objects\Traits\DetailTrait;
use Owlting\OwlPay\Objects\Traits\SecretTrait;

class VendorInvite extends BaseObject implements CreateInterface, DetailInterface, SecretInterface
{
    use CreateTrait;
    use DetailTrait;
    use SecretTrait;

    protected static $url_map = [
        self::CREATE => '/api/v1/platform/tunnel/vendor_invite',
//        self::SHOW_DETAIL => '/api/platform/orders/{order_token}',
    ];

    /**
     * VendorInvite constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }
}
