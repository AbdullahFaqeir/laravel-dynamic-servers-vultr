<?php

namespace AbdullahFaqeir\LaravelDynamicServersVultr\Vultr\Exceptions;

use Exception;
use Saloon\Contracts\Response;
use Spatie\DynamicServers\Models\Server;

class CannotGetVultrServerDetails extends Exception
{
    public static function make(
        Server $server,
        Response $response
    ): self {
        $reason = $response->json('error');

        return new self("Could not find instance: $reason");
    }
}
