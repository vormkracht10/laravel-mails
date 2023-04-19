<?php

namespace Vormkracht10\Mails\Listeners;

use Illuminate\Mail\Events\MessageSending;
use Vormkracht10\Mails\Actions\LogMail;

class LogSendingMail
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
    public function handle(MessageSending $event): void
    {
        (new LogMail)->execute($event);
    }
}
