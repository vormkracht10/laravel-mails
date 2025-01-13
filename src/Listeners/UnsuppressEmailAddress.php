<?php

namespace Vormkracht10\Mails\Listeners;

use Vormkracht10\Mails\Events\MailUnsuppressed;
use Vormkracht10\Mails\Facades\MailProvider;

class UnsuppressEmailAddress
{
    public function handle(MailUnsuppressed $event): void
    {
        MailProvider::with(driver: $event->driver)
            ->unsuppressEmailAddress(
                address: $event->emailAddress,
                stream_id: $event->stream_id ?? null
            );
    }
}
