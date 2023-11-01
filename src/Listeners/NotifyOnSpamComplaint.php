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
        if (! $channels = config('mails.events.complaint.notify')) {
            return;
        }

        foreach ($channels as $channel) {
            $notification = new SpamComplaintNotification($event->mailEvent->mail);

            $key = implode('.', ['mails', 'notifications', $channel, 'to']);

            $accounts = array_wrap(
                config($key, []),
            );

            if (empty($accounts)) {
                continue;
            }

            $this->send($notification, $channel, $accounts);
        }
    }
}
