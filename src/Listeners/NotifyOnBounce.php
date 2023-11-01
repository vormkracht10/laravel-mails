<?php

namespace Vormkracht10\Mails\Listeners;

use Vormkracht10\Mails\Events\MailBounced;
use Vormkracht10\Mails\Listeners\Concerns\SendsNotifications;
use Vormkracht10\Mails\Notifications\BounceNotification;

class NotifyOnBounce
{
    use SendsNotifications;

    public function handle(MailBounced $event): void
    {
        if (! $channels = config('mails.events.bounce.notify')) {
            return;
        }

        $notification = new BounceNotification($event->mailEvent->mail);

        foreach ($channels as $channel) {
            $this->send($notification, $channel);
        }
    }
}
