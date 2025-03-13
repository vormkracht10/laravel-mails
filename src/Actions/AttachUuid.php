<?php

namespace Vormkracht10\Mails\Actions;

use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Str;
use Vormkracht10\Mails\Facades\MailProvider;
use Vormkracht10\Mails\Shared\AsAction;

class AttachUuid
{
    use AsAction;

    public function handle(MessageSending $event): void
    {
        $provider = $this->getProvider($event);

        if (! $this->shouldTrackMails($provider)) {
            return;
        }

        $uuid = Str::uuid()->toString();

        $event->message->getHeaders()->addTextHeader(config('mails.headers.uuid'), $uuid);

        $event = MailProvider::with($provider)->attachUuidToMail($event, $uuid);
    }

    public function getProvider(MessageSending $event): string
    {
        return config('mail.mailers.'.$event->data['mailer'].'.transport') ?? $event->data['mailer'];
    }

    public function shouldTrackMails(string $provider): bool
    {
        return $this->trackingEnabled() &&
            $this->driverExistsForProvider($provider);
    }

    public function driverExistsForProvider(string $provider): bool
    {
        return class_exists('Vormkracht10\\Mails\\Drivers\\'.ucfirst($provider).'Driver');
    }

    public function trackingEnabled(): bool
    {
        return (bool) config('mails.logging.tracking.bounces') === true ||
            (bool) config('mails.logging.tracking.clicks') === true ||
            (bool) config('mails.logging.tracking.complaints') === true ||
            (bool) config('mails.logging.tracking.deliveries') === true ||
            (bool) config('mails.logging.tracking.opens') === true ||
            (bool) config('mails.logging.tracking.unsubscribes') === true;
    }
}
