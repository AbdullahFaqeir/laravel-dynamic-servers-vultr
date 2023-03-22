<?php

namespace AbdullahFaqeir\LaravelDynamicServersVultr\Connector;

use Saloon\Http\Connector;

class VultrConnector extends Connector
{
    public function __construct(
        protected string $token,
    ) {
        $this->withTokenAuth($this->token);
    }

    public function resolveBaseUrl(): string
    {
        return 'https://api.vultr.com/v2';
    }
}
