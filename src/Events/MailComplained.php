<?php

namespace Backstage\Mails\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Backstage\Mails\Models\MailEvent;

class MailComplained
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public MailEvent $mailEvent
    ) {}
}
