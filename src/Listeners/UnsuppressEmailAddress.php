<?php

namespace Vormkracht10\Mails\Listeners;

use Exception;
use Vormkracht10\Mails\Events\MailUnsuppressed;
use Vormkracht10\Mails\Facades\MailProvider;

class UnsuppressEmailAddress
{
    public function handle(MailUnsuppressed $event): void
    {
        $driver = match (config('mail.default')) {
            'postmark' => MailProvider::with(driver: 'postmark'),
            'mailgun' => MailProvider::with(driver: 'mailgun'),
            default =>
            MailProvider::with('default'),
        };

        $result = $driver->unSupress($event->mailEvent);

        if ($result) {
            $event->mailEvent->update(['unsuppressed_at', now()]);

            return;
        }

        throw new Exception('Failed to unsupress email address using the ' . config('mail.default') . ' driver: ');
    }
}
