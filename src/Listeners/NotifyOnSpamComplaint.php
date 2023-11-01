<?php

namespace Vormkracht10\Mails\Listeners;

use Vormkracht10\Mails\Events\MailComplained;
use Vormkracht10\Mails\Listeners\Concerns\SendsNotifications;
use Vormkracht10\Mails\Notifications\SpamComplaintNotification;

class NotifyOnSpamComplaint
{
    use SendsNotifications;

    /**
     * Handle the event.
     */
    public function handle(MailComplained $event): void
    {
        if (! $channels = config('mails.events.bounce.notify')) {
            return;
        }

        $notification = new SpamComplaintNotification($event->mailEvent->mail);

        foreach ($channels as $channel) {
            $this->send($notification, $channel);
        }
    }
}
