<?php

namespace AbdullahFaqeir\LaravelDynamicServersVultr\Vultr;

use AbdullahFaqeir\LaravelDynamicServersVultr\Enums\VultrPowerStatus;
use AbdullahFaqeir\LaravelDynamicServersVultr\Enums\VultrServerStatus;

class VultrServer
{
    public function __construct(
        public string            $id,
        public string            $title,
        public string            $ip,
        public VultrServerStatus $status,
        public VultrServerStatus $serverStatus,
        public VultrPowerStatus  $powerStatus
    )
    {
    }

    public static function fromApiPayload(array $payload): self
    {
        return new self(
            $payload['id'],
            $payload['label'],
            $payload['main_ip'],
            VultrServerStatus::from($payload['status']),
            VultrServerStatus::from($payload['server_status']),
            VultrPowerStatus::from($payload['power_status'])
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'ip' => $this->ip,
            'status' => $this->status->value,
            'server_status' => $this->serverStatus->value,
            'power_status' => $this->powerStatus->value
        ];
    }
}
