<?php

namespace Vormkracht10\Mails\Listeners;

use Vormkracht10\Mails\Events\MailUnsuppressed;
use Vormkracht10\Mails\Facades\MailProvider;

class UnsuppressEmailAddress
{
    public function handle(MailUnsuppressed $event): void
    {
        $driver = MailProvider::with(driver: $event->driver);

        if ($event->driver === 'postmark') {
            $driver->unsuppressEmailAddress(address: $event->emailAddress, stream_id: $event->stream_id);
        } else {
            $driver->unsuppressEmailAddress(address: $event->emailAddress);
        }
    }
}
