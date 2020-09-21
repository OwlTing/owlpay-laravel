<?php

namespace Owlting\OwlPay;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class OwlPayServiceProvider extends ServiceProvider
{
    /**
     *  Boot
     */
    public function boot()
    {
        parent::boot();
        $this->addConfig();
    }

    /**
     *  Config publishing
     */
    private function addConfig()
    {
        $this->publishes([
            __DIR__ . '/../../config/owlpay.php' => config_path('owlpay.php')
        ], 'config');
    }
}
