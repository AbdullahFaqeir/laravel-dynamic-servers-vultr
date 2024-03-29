<?php

namespace AbdullahFaqeir\LaravelDynamicServersVultr\Listeners;

use Spatie\DynamicServers\Events\ServerRunningEvent;

class UpdateServerMeta
{
    public function handle(ServerRunningEvent $event)
    {
        $event->server->addMeta(
            'server_properties',
            $event->server
                ->serverProvider()
                ->getServer()
                ->toArray()
        );
    }
}
