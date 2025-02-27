<?php

namespace Vormkracht10\Mails;

use Illuminate\Mail\Events\MessageSending;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\File;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Vormkracht10\Mails\Commands\CheckBounceRateCommand;
use Vormkracht10\Mails\Commands\MonitorMailCommand;
use Vormkracht10\Mails\Commands\PruneMailCommand;
use Vormkracht10\Mails\Commands\ResendMailCommand;
use Vormkracht10\Mails\Commands\WebhooksMailCommand;
use Vormkracht10\Mails\Contracts\MailProviderContract;
use Vormkracht10\Mails\Events\MailEvent;
use Vormkracht10\Mails\Events\MailHardBounced;
use Vormkracht10\Mails\Events\MailUnsuppressed;
use Vormkracht10\Mails\Listeners\AttachMailLogUuid;
use Vormkracht10\Mails\Listeners\LogMailEvent;
use Vormkracht10\Mails\Listeners\LogSendingMail;
use Vormkracht10\Mails\Listeners\LogSentMail;
use Vormkracht10\Mails\Listeners\NotifyOnBounce;
use Vormkracht10\Mails\Listeners\StoreMailRelations;
use Vormkracht10\Mails\Listeners\UnsuppressEmailAddress;
use Vormkracht10\Mails\Managers\MailProviderManager;

class MailsServiceProvider extends PackageServiceProvider
{
    public function registeringPackage(): void
    {
        $this->app['events']->listen(MailEvent::class, LogMailEvent::class);

        $this->app['events']->listen(MessageSending::class, AttachMailLogUuid::class);
        $this->app['events']->listen(MessageSending::class, LogSendingMail::class);
        $this->app['events']->listen(MessageSent::class, LogSentMail::class);

        $this->app['events']->listen(MailHardBounced::class, NotifyOnBounce::class);

        $this->app['events']->listen(MessageSending::class, StoreMailRelations::class);

        $this->app['events']->listen(MailUnsuppressed::class, UnsuppressEmailAddress::class);
    }

    public function bootingPackage(): void
    {
        $this->app->singleton(MailProviderContract::class, fn ($app) => new MailProviderManager($app));
    }

    public function configurePackage(Package $package): void
    {
        $migrations = collect(File::allFiles(__DIR__.'/../database/migrations'))
            ->map(fn ($file) => str_replace('.php.stub', '', $file->getFilename()))
            ->toArray();

        $package
            ->name('laravel-mails')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigrations($migrations)
            ->hasRoutes('webhooks')
            ->hasCommands(
                MonitorMailCommand::class,
                PruneMailCommand::class,
                ResendMailCommand::class,
                WebhooksMailCommand::class,
                CheckBounceRateCommand::class,
            );
    }
}
