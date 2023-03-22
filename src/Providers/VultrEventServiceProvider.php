<?php

namespace AbdullahFaqeir\LaravelDynamicServersVultr\Providers;

use AbdullahFaqeir\LaravelDynamicServersVultr\Listeners\UpdateServerMeta;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Spatie\DynamicServers\Events\ServerRunningEvent;

class VultrEventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ServerRunningEvent::class => [
            UpdateServerMeta::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot(): void
    {
        parent::boot();
    }
}
