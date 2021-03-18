<?php

namespace App\Providers;

use App\Channels\AwsSnsSmsChannel;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class ChannelServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        Notification::resolved(function (ChannelManager $service) {
            $service->extend('aws_sns_sms_channel', function ($app) {
                return new AwsSnsSmsChannel(
                    $this->app->make('aws')->createClient('sns')
                );
            });
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
