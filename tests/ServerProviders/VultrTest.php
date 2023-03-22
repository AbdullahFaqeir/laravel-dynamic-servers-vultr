<?php

use AbdullahFaqeir\LaravelDynamicServersVultr\Enums\VultrPowerStatus;
use AbdullahFaqeir\LaravelDynamicServersVultr\Enums\VultrServerStatus;
use AbdullahFaqeir\LaravelDynamicServersVultr\Vultr\VultrServer;
use AbdullahFaqeir\LaravelDynamicServersVultr\Vultr\VultrServerProvider;
use Illuminate\Support\Str;
use Spatie\DynamicServers\Models\Server;

beforeEach(function () {
    if (! $this->vultrHasBeenConfigured()) {
        $this->markTestSkipped('Vultr not configured');
    }

    $server = Server::factory()->create([
        'provider' => 'vultr',
        'type' => 'small',
    ])->addMeta('server_properties.uuid', Str::uuid());

    $vultrServer = new VultrServer(
        id: 'bae260f4-0b9c-4825-a2fb-22bb1583c9dc',
        title: 'server-name',
        ip: '45.32.239.199',
        status: VultrServerStatus::RUNNING,
        serverStatus: VultrServerStatus::RUNNING,
        powerStatus: VultrPowerStatus::STOPPED
    );

    $server->addMeta('server_properties', $vultrServer->toArray());

    $this->vultrServerProvider = (new VultrServerProvider())->setServer($server);
});

it('can delete instance', function () {
    $this->vultrServerProvider->deleteServer();
})->expectNotToPerformAssertions();

it('can determine that the server has been deleted', function () {
    expect($this->vultrServerProvider->hasBeenDeleted())->toBeTrue();
});

it('can determine the total number of servers on Vultr', function () {
    expect($this->vultrServerProvider->currentServerCount())->toBeInt();
});

it('can check if instance started', function () {
    expect($this->vultrServerProvider->hasStarted())->toBeTrue();
});

it('can stop instance', function () {
    $this->vultrServerProvider->stopServer();
})->expectNotToPerformAssertions();

it('can check if instance is stopped', function () {
    expect($this->vultrServerProvider->hasStopped())->toBeTrue();
});

it('can reboot instance', function () {
    $this->vultrServerProvider->rebootServer();
})->expectNotToPerformAssertions();

it('can update instance meta data', function () {
    $this->vultrServerProvider->updateServerMeta();
})->expectNotToPerformAssertions();

it('can create instance on Vultr', function () {
    $this->vultrServerProvider->createServer();
})->expectNotToPerformAssertions();
