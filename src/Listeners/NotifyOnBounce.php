<?php

namespace Vormkracht10\Mails\Listeners;

use Illuminate\Support\Facades\Notification;
use Vormkracht10\Mails\Notifications\BounceNotification;

class NotifyOnBounce
{
    public function handle($event): void
    {
        logger()->debug('xxx');

        if (null !== $notificationChannels = config('mails.notifications.events.bounce.notify')) {
            collect($notificationChannels)->each(function ($channel) {
                collect(array_wrap($channel))->each(function ($account, $route) {
                    Notification::route($route, $account)
                        ->send(new BounceNotification($event->mail));
                });
            });
        }
    }
}
