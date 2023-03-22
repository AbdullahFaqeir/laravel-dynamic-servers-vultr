<?php

namespace AbdullahFaqeir\LaravelDynamicServersVultr\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetInstanceRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $instanceId,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/instances/$this->instanceId";
    }
}
