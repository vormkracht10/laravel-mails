<?php

namespace Vormkracht10\Mails\Listeners;

use Illuminate\Support\Facades\Notification;
use Vormkracht10\Mails\Events\MailBounced;
use Vormkracht10\Mails\Notifications\BounceNotification;

class NotifyOnBounce
{
    public function handle(MailBounced $event): void
    {
        if (null !== $notificationChannels = config('mails.notifications.events.bounce.notify')) {
            collect($notificationChannels)->each(function ($channel) use ($event) {
                $accounts = config('mails.notifications.channels.'.$channel);

                collect(array_wrap($accounts))->each(function ($account, $route) use ($channel, $event) {
                    Notification::route($channel, $account)
                        ->notify(new BounceNotification($event->mailEvent->mail));
                });
            });
        }
    }
}
