<?php

namespace Backstage\Mails\Listeners;

use Backstage\Mails\Events\MailUnsuppressed;
use Backstage\Mails\Facades\MailProvider;

class UnsuppressEmailAddress
{
    public function handle(MailUnsuppressed $event): void
    {
        MailProvider::with(driver: $event->mailer)
            ->unsuppressEmailAddress(
                address: $event->emailAddress,
                stream_id: $event->stream_id ?? null
            );
    }
}
