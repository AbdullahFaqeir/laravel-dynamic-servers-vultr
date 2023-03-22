<?php

namespace AbdullahFaqeir\LaravelDynamicServersVultr\Requests;

use Saloon\Contracts\Body\HasBody as HasBodyContract;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasFormBody;

class GetInstanceRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $instanceId,
    )
    {
    }

    public function resolveEndpoint(): string
    {
        return "/instances/$this->instanceId";
    }
}
