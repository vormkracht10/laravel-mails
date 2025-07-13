<?php

namespace Backstage\Mails\Listeners;

use Backstage\Mails\Events\MailHardBounced;
use Backstage\Mails\Notifications\BounceNotification;
use Backstage\Mails\Traits\SendsNotifications;

class NotifyOnBounce
{
    use SendsNotifications;

    public function handle(MailHardBounced $event): void
    {
        if (! $channels = config('mails.events.bounce.notify')) {
            return;
        }

        $notification = new BounceNotification($event->mailEvent->mail);

        $this->send($notification, $channels);
    }
}
