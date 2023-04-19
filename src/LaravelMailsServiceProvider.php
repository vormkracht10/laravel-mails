<?php

namespace Vormkracht10\Mails;

use Illuminate\Mail\Events\MessageSending;
use Illuminate\Mail\Events\MessageSent;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Vormkracht10\Mails\Commands\MonitorMailCommand;
use Vormkracht10\Mails\Commands\PruneMailCommand;
use Vormkracht10\Mails\Commands\ResendMailCommand;
use Vormkracht10\Mails\Commands\WebhooksMailCommand;
use Vormkracht10\Mails\Listeners\LogSendingMail;
use Vormkracht10\Mails\Listeners\LogSentMail;

class LaravelMailsServiceProvider extends PackageServiceProvider
{
    public function register(): void
    {
        $this->app['events']->listen(MessageSending::class, LogSendingMail::class);
        $this->app['events']->listen(MessageSent::class, LogSentMail::class);

        parent::register();
    }

    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-mails')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigrations([
                'create_mailables_table',
                'create_mails_events_table',
                'create_mails_table',
            ])
            ->hasCommands([
                MonitorMailCommand::class,
                PruneMailCommand::class,
                ResendMailCommand::class,
                WebhooksMailCommand::class,
            ]);
    }
}
