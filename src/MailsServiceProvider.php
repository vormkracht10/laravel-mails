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
use Vormkracht10\Mails\Events\MailBounced;
use Vormkracht10\Mails\Events\WebhookEvent;
use Vormkracht10\Mails\Listeners\AttachMailLogUuid;
use Vormkracht10\Mails\Listeners\LogMailEvent;
use Vormkracht10\Mails\Listeners\LogSendingMail;
use Vormkracht10\Mails\Listeners\LogSentMail;
use Vormkracht10\Mails\Listeners\NotifyOnBounce;
use Vormkracht10\Mails\Listeners\RelayWebhookEventsListener;
use Vormkracht10\Mails\Listeners\StoreMailRelations;
use Vormkracht10\Mails\Managers\MailProviderManager;

class MailsServiceProvider extends PackageServiceProvider
{
    public function registeringPackage(): void
    {
        $this->app['events']->listen(WebhookEvent::class, LogMailEvent::class);
        $this->app['events']->listen(WebhookEvent::class, RelayWebhookEventsListener::class);

        $this->app['events']->listen(MessageSending::class, AttachMailLogUuid::class);
        $this->app['events']->listen(MessageSending::class, LogSendingMail::class);
        $this->app['events']->listen(MessageSent::class, LogSentMail::class);

        $this->app['events']->listen(MailBounced::class, NotifyOnBounce::class);

        $this->app['events']->listen(MessageSending::class, StoreMailRelations::class);
    }

    public function bootingPackage(): void
    {
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
                'create_mail_attachments_table',
                'create_mail_events_table',
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
