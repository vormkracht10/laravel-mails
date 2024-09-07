<?php

namespace Vormkracht10\Mails\Listeners;

use Illuminate\Mail\Events\MessageSent;
use Vormkracht10\Mails\Actions\LogMail;

class LogSentMail
{
    public function handle(MessageSent $event): void
    {
        (new LogMail)($event);
    }
}
