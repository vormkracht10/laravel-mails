<?php

namespace Vormkracht10\Mails\Listeners;

use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Mail\Events\MessageSending;
use Vormkracht10\Mails\Actions\LogMail;

class LogSendingMail
{
    protected Mailer $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function handle(MessageSending $event): void
    {
        (new LogMail)($event, $this->mailer);
    }
}
