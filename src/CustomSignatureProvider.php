<?php

namespace Karu\CustomSignature;

use Illuminate\Support\ServiceProvider;

class CustomSignatureProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/customSignature.php' => config_path('customSignature.php'),
        ]);
    }
}
