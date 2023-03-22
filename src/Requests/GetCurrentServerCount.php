<?php

namespace AbdullahFaqeir\LaravelDynamicServersVultr\Requests;

use Saloon\Contracts\Body\HasBody as HasBodyContract;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasFormBody;

class GetCurrentServerCount extends Request implements HasBodyContract
{
    use HasFormBody;

    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $region,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/instances';
    }

    protected function defaultBody(): array
    {
        return [
            'region' => $this->region,
        ];
    }
}
