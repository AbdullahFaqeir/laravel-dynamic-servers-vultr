<?php

namespace AbdullahFaqeir\LaravelDynamicServersVultr;

use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelDynamicServersVultrServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-dynamic-servers-vultr')
            ->publishesServiceProvider('VultrEventServiceProvider')
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->copyAndRegisterServiceProviderInApp()
                    ->endWith(function (InstallCommand $installCommand) {
                        $installCommand->line('');
                        $installCommand->info("We've added app\Providers\VultrEventServiceProvider to your project.");
                        $installCommand->info('Feel free to customize it to your needs.');
                    });
            });
    }
}
