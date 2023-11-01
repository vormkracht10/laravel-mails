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

        foreach ($channels as $channel) {
            $notification = new BounceNotification($event->mailEvent->mail);

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
