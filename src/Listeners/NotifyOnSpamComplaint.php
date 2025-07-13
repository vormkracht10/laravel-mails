<?php

namespace Backstage\Mails\Listeners;

use Backstage\Mails\Events\MailComplained;
use Backstage\Mails\Notifications\SpamComplaintNotification;
use Backstage\Mails\Traits\SendsNotifications;

class NotifyOnSpamComplaint
{
    use SendsNotifications;

    public function handle(MailComplained $event): void
    {
        if (! $channels = config('mails.events.complaint.notify')) {
            return;
        }

        $notification = new SpamComplaintNotification($event->mailEvent->mail);

        $this->send($notification, $channels);
    }
}
