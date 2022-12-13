<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        RateLimiter::for('backups', function ($job) {
            return Limit::perMinutes(1,1)->by($job->data['id']);
        });

        Queue::before(function (JobProcessing $event) {
            Log::info('connectionName'. $event->connectionName);
            Log::info('payload',$event->job->payload());
        });

        Queue::after(function (JobProcessed $event) {
            Log::info('connectionName'. $event->connectionName);
            Log::info('payload',$event->job->payload());
        });
    }
}
