<?php

namespace Vormkracht10\Mails\Listeners;

class UnsupsressEmailAddress
{
    public function handle(MailUnsuppressed $event): void
    {
        $event->mailEvent->update('unsuppressed_at', now());
    }
}
