<?php

namespace Vormkracht10\Mails\Listeners;

use Illuminate\Support\Facades\Notification;
use Vormkracht10\Mails\Events\MailBounced;
use Vormkracht10\Mails\Notifications\BounceNotification;
use Vormkracht10\Mails\Notifications\Concerns\HasDynamicDrivers;

class NotifyOnBounce
{
    public function handle(MailBounced $event): void
    {
        if (! $channels = config('mails.events.bounce.notify')) {
            return;
        }

        foreach ($channels as $channel) {
            $notification = new BounceNotification($event->mailEvent->mail);

            $key = join('.', ['mails', 'notifications', $channel, 'to']);

            $accounts = array_wrap(
                config($key, []),
            );

            if (empty($accounts)) continue;

            $this->send($notification, $channel, $accounts);
        }
    }

    /**
     * @param HasDynamicDrivers $notification
     */
    protected function send(mixed $notification, string $channel, array $to): void
    {
        Notification::route($channel, $to)->notify(
            $notification->on($channel),
        );
    }
}
