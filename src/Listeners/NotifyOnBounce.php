<?php

namespace Vormkracht10\Mails\Listeners;

class NotifyOnBounce
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
    public function handle($event): void
    {
        // Access the order using $event->order...
    }
}
