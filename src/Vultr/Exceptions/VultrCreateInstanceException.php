<?php

namespace AbdullahFaqeir\LaravelDynamicServersVultr\Vultr\Exceptions;

use Exception;
use Saloon\Contracts\Response;
use Spatie\DynamicServers\Models\Server;

class VultrCreateInstanceException extends Exception
{
    public static function make(
        Server   $server,
        Response $response
    ): self
    {
        $reason = $response->json('error');

        return new self("Could not create instance: $reason");
    }
}
