<?php

namespace NotificationChannels\Mandrill;

use Illuminate\Support\ServiceProvider;
use Mandrill;

class MandrillServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->when(MandrillChannel::class)
            ->needs(Mandrill::class)
            ->give(function () {
                $apiKey = config('services.mandrill.secret');

                return new Mandrill($apiKey);
            });
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $source = realpath(__DIR__.'/../config/services.php');

        $this->mergeConfigFrom($source, 'services.mandrill');
    }
}
