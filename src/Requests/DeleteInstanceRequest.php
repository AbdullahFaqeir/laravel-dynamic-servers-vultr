<?php

namespace AbdullahFaqeir\LaravelDynamicServersVultr\Requests;

use Saloon\Contracts\Body\HasBody as HasBodyContract;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class DeleteInstanceRequest extends Request implements HasBodyContract
{
    use HasJsonBody;

    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly string $instanceId,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/instances/$this->instanceId";
    }
}
