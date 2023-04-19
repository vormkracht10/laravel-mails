<?php

namespace Vormkracht10\LaravelMails;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Vormkracht10\LaravelMails\Commands\LaravelMailsCommand;

class LaravelMailsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-mails')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel-mails_table')
            ->hasCommand(LaravelMailsCommand::class);
    }
}
