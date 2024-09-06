<?php

namespace Vormkracht10\Mails\Listeners;

use Vormkracht10\Mails\Events\MailEvent;
use Vormkracht10\Mails\Facades\MailProvider;

class LogMailEvent
{
    /**
     * Handle the event.
     */
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

    private function dispatch($payload): void
    {
        dispatch(fn () => $this->record($payload));
    }
}
