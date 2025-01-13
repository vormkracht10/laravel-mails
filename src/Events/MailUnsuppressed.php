<?php

namespace Vormkracht10\Mails\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Vormkracht10\Mails\Models\MailEvent;

class MailUnsuppressed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public MailEvent $mailEvent
    ) {}
}
