<?php

namespace AbdullahFaqeir\LaravelDynamicServersVultr\Vultr;

use AbdullahFaqeir\LaravelDynamicServersVultr\Connector\VultrConnector;
use AbdullahFaqeir\LaravelDynamicServersVultr\Enums\VultrPowerStatus;
use AbdullahFaqeir\LaravelDynamicServersVultr\Enums\VultrServerStatus;
use AbdullahFaqeir\LaravelDynamicServersVultr\Requests\CreateInstanceRequest;
use AbdullahFaqeir\LaravelDynamicServersVultr\Requests\DeleteInstanceRequest;
use AbdullahFaqeir\LaravelDynamicServersVultr\Requests\GetCurrentServerCount;
use AbdullahFaqeir\LaravelDynamicServersVultr\Requests\GetInstanceRequest;
use AbdullahFaqeir\LaravelDynamicServersVultr\Requests\HaltInstanceRequest;
use AbdullahFaqeir\LaravelDynamicServersVultr\Requests\RebootInstanceRequest;
use AbdullahFaqeir\LaravelDynamicServersVultr\Vultr\Exceptions\CannotGetVultrServerDetails;
use AbdullahFaqeir\LaravelDynamicServersVultr\Vultr\Exceptions\VultrCreateInstanceException;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Spatie\DynamicServers\ServerProviders\ServerProvider;

class VultrServerProvider extends ServerProvider
{
    /**
     * @Done
     */
    public function createServer(): void
    {
        $createInstance = $this->connector()->send(
            new CreateInstanceRequest(
                region: $this->server->option('region'),
                configuration: $this->server->configuration
            )
        );

        if ($createInstance->status() !== 202) {
            throw new VultrCreateInstanceException($this->server, $createInstance);
        }

        $vultrServer = VultrServer::fromApiPayload($createInstance->json('instance'));
    }

    /**
     * @Done
     */
    public function updateServerMeta(): void
    {
        $vultrInstance = $this->getInstance();

        $this->server->addMeta('server_properties', $vultrInstance->toArray());
    }

    /**
     * @Done
     */
    public function hasStarted(): bool
    {
        $instance = $this->getInstance();

        return $instance->serverStatus === VultrServerStatus::OK && $instance->status === VultrServerStatus::ACTIVE;
    }

    /**
     * @Done
     */
    public function stopServer(): void
    {
        $instanceId = $this->server->meta('server_properties.id');
        $this->connector()->send(new HaltInstanceRequest($instanceId));
    }

    /**
     * @Done
     */
    public function hasStopped(): bool
    {
        return $this->getInstance()->powerStatus === VultrPowerStatus::STOPPED;
    }

    /**
     * @Done
     */
    public function deleteServer(): void
    {
        $instanceId = $this->server->meta('server_properties.id');
        $this->connector()->send(new DeleteInstanceRequest(
            instanceId: $instanceId
        ));
    }

    /**
     * @Done
     */
    public function hasBeenDeleted(): bool
    {
        $serverId = Str::of($this->server->meta('server_properties.id'))->substrReplace('22', 34, 2);

        $instance = $this->connector()->send(new GetInstanceRequest($serverId));

        return $instance->status() !== 200;
    }

    /**
     * @Done
     */
    public function rebootServer(): void
    {
        $instanceId = $this->server->meta('server_properties.id');
        $this->connector()->send(new RebootInstanceRequest(
            instanceId: $instanceId
        ));
    }

    /**
     * @Done
     */
    public function currentServerCount(): int
    {
        $currentServerCount = $this->connector()->send(
            new GetCurrentServerCount(
                $this->server->option('region')
            )
        )->json();

        return Arr::get($currentServerCount, 'meta.total', 0);
    }

    /**
     * @Done
     */
    public function getInstance(): VultrServer
    {
        $instanceId = $this->server->meta('server_properties.id');

        $response = $this->connector()->send(
            new GetInstanceRequest(
                instanceId: $instanceId
            )
        );

        if (! $response->successful()) {
            throw CannotGetVultrServerDetails::make($this->server, $response);
        }

        return VultrServer::fromApiPayload($response->json('instance'));
    }

    /**
     * @Done
     */
    protected function connector(): VultrConnector
    {
        return new VultrConnector($this->server->option('token'));
    }
}
