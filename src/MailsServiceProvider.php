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
use Vormkracht10\Mails\Contracts\MailProviderContract;
use Vormkracht10\Mails\Events\MailEvent;
use Vormkracht10\Mails\Listeners\AttachMailLogUuid;
use Vormkracht10\Mails\Listeners\LogMailEvent;
use Vormkracht10\Mails\Listeners\LogSendingMail;
use Vormkracht10\Mails\Listeners\LogSentMail;
use Vormkracht10\Mails\Managers\MailProviderManager;

class MailsServiceProvider extends PackageServiceProvider
{
    public function register(): void
    {
        parent::register();

        $this->app['events']->listen(MailEvent::class, LogMailEvent::class);

        $this->app['events']->listen(MessageSending::class, AttachMailLogUuid::class);
        $this->app['events']->listen(MessageSending::class, LogSendingMail::class);
        $this->app['events']->listen(MessageSent::class, LogSentMail::class);
    }

    public function boot(): void
    {
        parent::boot();

        $this->app->singleton(MailProviderContract::class, fn ($app) => new MailProviderManager($app));
    }

    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-mails')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigrations(
                'create_mailables_table',
                'create_mails_attachments_table',
                'create_mails_events_table',
                'create_mails_table',
            )
            ->hasRoutes('webhooks')
            ->hasCommands(
                MonitorMailCommand::class,
                PruneMailCommand::class,
                ResendMailCommand::class,
                WebhooksMailCommand::class,
            );
    }
}
