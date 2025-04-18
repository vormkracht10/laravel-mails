<?php

namespace Vormkracht10\Mails;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Mail\Events\MessageSent;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use SplFileInfo;
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
use Vormkracht10\Mails\Listeners\ResendLogMailEvent;
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
        $this->app['events']->listen(MessageSending::class, StoreMailRelations::class);

        $this->app['events']->listen(MessageSent::class, LogSentMail::class);
        $this->app['events']->listen(MailHardBounced::class, NotifyOnBounce::class);
        $this->app['events']->listen(MailUnsuppressed::class, UnsuppressEmailAddress::class);

        if (class_exists('Resend\Laravel\ResendServiceProvider')) {
            $this->app['events']->listen('Resend\Laravel\Events\EmailSent', ResendLogMailEvent::class);
            $this->app['events']->listen('Resend\Laravel\Events\EmailBounced', ResendLogMailEvent::class);
            $this->app['events']->listen('Resend\Laravel\Events\EmailClicked', ResendLogMailEvent::class);
            $this->app['events']->listen('Resend\Laravel\Events\EmailComplained', ResendLogMailEvent::class);
            $this->app['events']->listen('Resend\Laravel\Events\EmailDelivered', ResendLogMailEvent::class);
            $this->app['events']->listen('Resend\Laravel\Events\EmailDeliveredDelayed ', ResendLogMailEvent::class);
            $this->app['events']->listen('Resend\Laravel\Events\EmailOpened', ResendLogMailEvent::class);
        }
    }

    public function bootingPackage(): void
    {
        $this->app->singleton(MailProviderContract::class, fn($app) => new MailProviderManager($app));
    }

    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-mails')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigrations($this->getMigrations())
            ->hasRoutes('webhooks')
            ->hasCommands(
                MonitorMailCommand::class,
                PruneMailCommand::class,
                ResendMailCommand::class,
                WebhooksMailCommand::class,
                CheckBounceRateCommand::class,
            );
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return collect(app(Filesystem::class)->files(__DIR__ . '/../database/migrations'))
            ->map(fn(SplFileInfo $file) => str_replace('.php.stub', '', $file->getBasename()))
            ->toArray();
    }
}
