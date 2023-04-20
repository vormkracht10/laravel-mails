<?php

namespace Vormkracht10\Mails\Listeners;

use Illuminate\Mail\Events\MessageSent;
use Vormkracht10\Mails\Actions\LogMail;

class LogSentMail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        // ...
    }

    /**
     * Handle the event.
     */
    public function handle(MessageSent $event): void
    {
        (new LogMail)($event);
    }
}
