<?php

namespace AbdullahFaqeir\LaravelDynamicServersVultr\Tests;

use AbdullahFaqeir\LaravelDynamicServersVultr\LaravelDynamicServersVultrServiceProvider;
use AbdullahFaqeir\LaravelDynamicServersVultr\Tests\TestSupport\ServerProviders\DummyServerProvider;
use Illuminate\Support\Str;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\DynamicServers\DynamicServersServiceProvider;
use Spatie\DynamicServers\Facades\DynamicServers;
use Spatie\DynamicServers\Models\Server;
use Spatie\DynamicServers\Support\ServerTypes\ServerType;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpVultrTestProvider();
    }

    protected function getPackageProviders($app): array
    {
        return [
            DynamicServersServiceProvider::class,
            LaravelDynamicServersVultrServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        $migration = include __DIR__.'/../vendor/spatie/laravel-dynamic-servers/database/migrations/create_dynamic_servers_table.php';

        $migration->up();
    }

    protected function setUpVultrTestProvider(): self
    {
        $this->setDefaultServerProvider(DummyServerProvider::class);

        $this->setDefaultOptions();

        $providerConfig = config('dynamic-servers.providers');
        $providerConfig['other_provider'] = [
            'class' => DummyServerProvider::class,
        ];

        config()->set('dynamic-servers.providers', $providerConfig);

        $vultrServer = ServerType::new('small')
            ->provider('vultr')
            ->configuration(function (Server $server) {
                return [
                    'label' => Str::slug($server->name),
                    'region' => $server->option('region'),
                    'plan' => 'vc2-1c-1gb',
                    'vpc_uuid' => '62420e18-5628-4f6c-9ee4-aca48a5a7c17',
                    'os_id' => 1743,
                    'enable_ipv6' => true,
                    'backups' => 'disabled',
                    'tags' => [
                        'tag1',
                        'tag2',
                    ],
                ];
            });

        DynamicServers::registerServerType($vultrServer);

        return $this;
    }

    public function setDefaultOptions()
    {
        config()->set('dynamic-servers.providers.vultr.options', [
            'token' => env('VULTR_TOKEN', 'R6RX44O3YWVU5VMND5B6C3MRC4HL7BAYT5TQ'),
            'region' => env('VULTR_REGION', 'ams'),
        ]);
    }

    protected function setDefaultServerProvider(string $serverProvider): self
    {
        config()->set('dynamic-servers.providers.vultr.class', $serverProvider);

        return $this;
    }

    public function vultrHasBeenConfigured(): bool
    {
        return config('dynamic-servers.providers.vultr.options.token') !== null;
    }
}
