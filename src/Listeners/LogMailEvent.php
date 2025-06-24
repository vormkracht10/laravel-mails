<?php

namespace Backstage\Mails\Listeners;

use Backstage\Mails\Events\MailEvent;
use Backstage\Mails\Facades\MailProvider;

class LogMailEvent
{
    public function handle(MailEvent $event): void
    {
        $mail = MailProvider::with($event->provider)->getMailFromPayload($event->payload);

        if (! $mail) {
            return;
        }

        if (config('mails.webhooks.queue')) {
            $this->dispatch($event->provider, $event->payload);

            return;
        }

        $this->record($event->provider, $event->payload);
    }

    private function record($provider, $payload): void
    {
        MailProvider::with($provider)
            ->logMailEvent($payload);
    }

    private function dispatch($provider, $payload): void
    {
        dispatch(fn () => $this->record($provider, $payload));
    }
}
