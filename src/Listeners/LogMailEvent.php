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
        $mail = $event->mail();

        if (! $mail) {
            return;
        }

        if (config('mails.webhooks.queue')) {
            $this->dispatch($mail, $event);

            return;
        }

        $this->record($mail, $event);
    }

    private function dispatch($mail, $event): void
    {
        dispatch(fn () => $this->record($mail, $event));
    }

    private function record($mail, $event): void
    {
        MailProvider::with($event->provider->name)
            ->record($mail, $event->type, $event->payload, $event->timestamp);
    }
}
