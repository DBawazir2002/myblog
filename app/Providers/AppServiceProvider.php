<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Twilio\Rest\Client;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Client::class, function ($app) {
            return new Client(
                getenv("TWILIO_ACCOUNT_SID"),
                getenv("TWILIO_AUTH_TOKEN"),
            );
        });
    }
}
