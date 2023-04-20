<?php

namespace Vormkracht10\Mails\Listeners;

use Vormkracht10\Mails\Facades\MailProvider;

class LogMailEvent
{
    /**
     * Handle the event.
     */
    public function handle($event): void
    {
        MailProvider::with($event->provider)
            ->record($event);
    }
}
