<?php

namespace Vormkracht10\Mails\Listeners;

use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Mail\Events\MessageSent;
use Vormkracht10\Mails\Actions\LogMail;

class LogSentMail
{
    protected Mailer $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function handle(MessageSent $event): void
    {
        (new LogMail)($event, $this->mailer);
    }
}
