<?php

namespace Vormkracht10\Mails\Listeners;

use Vormkracht10\Mails\Enums\Provider;
use Vormkracht10\Mails\Events\MailEvent;

class ResendLogMailEvent
{
    public function handle($event): void
    {
        MailEvent::dispatch(Provider::RESEND->value, $event->payload);
    }
}
