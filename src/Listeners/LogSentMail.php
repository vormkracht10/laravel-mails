<?php

namespace Backstage\Mails\Listeners;

use Illuminate\Mail\Events\MessageSent;
use Backstage\Mails\Actions\LogMail;

class LogSentMail
{
    public function handle(MessageSent $event): void
    {
        (new LogMail)($event);
    }
}
