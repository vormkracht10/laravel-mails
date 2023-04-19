<?php

namespace Vormkracht10\Mails\Listeners;

use Illuminate\Mail\Events\MessageSending;
use Vormkracht10\Mails\Actions\AttachUuid;

class AttachMailLogUuid
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
        (new AttachUuid)->execute($event);
    }
}
