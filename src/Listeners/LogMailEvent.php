<?php

namespace Vormkracht10\Mails\Listeners;

use Vormkracht10\Mails\Facades\MailProvider;

class LogMailEvent
{
    /**
     * Handle the event.
     */
    public function handle($provider, $payload): void
    {
        if (config('mails.webhooks.queue')) {
            $this->dispatch($provider, $payload);

            return;
        }

        $this->record($provider, $payload);
    }

    private function dispatch($provider, $payload): void
    {
        dispatch(fn () => $this->record($provider, $payload));
    }

    private function record($provider, $payload): void
    {
        MailProvider::with($provider)
            ->record($payload);
    }
}
