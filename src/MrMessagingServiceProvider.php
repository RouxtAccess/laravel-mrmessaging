<?php

namespace Illuminate\Notifications;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Notification;

class MrMessagingServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/mrmessaging.php' => config_path('mrmessaging.php')
        ], 'laravel-mrmessaging-config');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/mrmessaging.php', 'mrmessaging'
        );

        Notification::extend('mrmessaging', function ($app) {
            return new Channels\MrMessagingChannel();
        });
    }
}
