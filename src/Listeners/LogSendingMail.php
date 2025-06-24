<?php

namespace Backstage\Mails\Listeners;

use Illuminate\Mail\Events\MessageSending;
use Backstage\Mails\Actions\LogMail;

class LogSendingMail
{
    public function handle(MessageSending $event): void
    {
        (new LogMail)($event);
    }
}
