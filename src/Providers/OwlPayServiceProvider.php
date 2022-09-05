<?php

namespace Owlting\OwlPay\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class OwlPayServiceProvider extends ServiceProvider
{
    public function boot()
    {
        parent::boot();

        $this->addConfig();
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/owlpay.php', 'owlpay');
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
