# Vultr provider for Spatie's Dynamic Servers Package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/abdullahfaqeir/laravel-dynamic-servers-vultr.svg?style=flat-square)](https://packagist.org/packages/abdullahfaqeir/laravel-dynamic-servers-vultr)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/abdullahfaqeir/laravel-dynamic-servers-vultr/run-tests?label=tests)](https://github.com/abdullahfaqeir/laravel-dynamic-servers-vultr/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/abdullahfaqeir/laravel-dynamic-servers-vultr/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/abdullahfaqeir/laravel-dynamic-servers-vultr/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/abdullahfaqeir/laravel-dynamic-servers-vultr.svg?style=flat-square)](https://packagist.org/packages/abdullahfaqeir/laravel-dynamic-servers-vultr)

This package provides a <a href="https://vultr.com"> Server Provider for
Spatie's <a href="https://github.com/spatie/laravel-dynamic-servers">Laravel Dynamic Servers</a> Package.

## Installation

You can install the package via composer:

```bash
composer require abdullahfaqeir/laravel-dynamic-servers-vultr
```

Afterward make sure to publish the EventServiceProvider that comes with this package:

```bash
php artisan dynamic-servers-vultr:install
```

## Usage

In your config/dynamic-servers.php register the Vulter provider

```php
return [
    'providers' => [
        'vultr' => [
            'class' => AbdullahFaqeir\LaravelDynamicServersVultr\Vultr\VultrServerProvider::class,
            'maximum_servers_in_account' => 15,
            'options' => [
                'token' => env('VULTR_TOKEN'),
                'region' => env('VULTR_REGION'),
            ],
        ],
    ],
];
```

In your app/Providers/DynamicServersProvider.php register a new server type using the Vultr provider

```php
public function register()
{
    ....
    
    
    $vultrServer = ServerType::new('small')
        ->provider('vultr')
        ->configuration(function (Server $server) {
            return [
                'label' => Str::slug($server->name),
                "region" => $server->option('region'),
                'plan' => 'vc2-1c-1gb',
                "vpc_uuid" => '62420e18-5628-4f6c-9ee4-aca48a5a7c17',
                'os_id' => 1743,
                'enable_ipv6' => true,
                'backups' => 'disabled',
                'tags' => [
                    'tag1',
                    'tag2'
                ]
            ];
        });

    DynamicServers::registerServerType($vultrServer);
}
```

## Events

After the base package's `CreateServerJob` is executed, a new job, `VerifyServerStartedJob` will be dispatched and will
check every 20 seconds to make sure that the provider eventually marks the Instance as running.

After it ensures it runs, no other attempt is made to fetch again the server meta.

Considering that Vultr will return the ip address of a instance only after it has been full created we need to fetch
once more the instance meta.

For this, we will use the base package's event 'ServerRunningEvent'.

This package, publishes a `App\Providers\VultrEventServiceProvider` in your project.

By default there is a single listener, configured and it will fetch again the Instance's meta after the base package has
ensured that it is running.

```php
protected $listen = [
        ServerRunningEvent::class => [
            UpdateServerMeta::class,
        ],
    ];
```

You may customise the listener, disable it or replace it with a your own.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.



## Credits

- [Abdullah Al-Faqeir](https://github.com/abdullahfaqeir)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
