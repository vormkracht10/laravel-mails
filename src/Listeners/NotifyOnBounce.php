<?php

namespace Vormkracht10\Mails\Listeners;

use Illuminate\Support\Facades\Notification;
use Vormkracht10\Mails\Notifications\BounceNotification;

class NotifyOnBounce
{
    public function handle($event): void
    {
        if (null !== $notificationChannels = config('mails.notifications.events.bounce.notify')) {
            collect($notificationChannels)->each(function ($channel) use ($event) {
                collect(array_wrap($channel))->each(function ($account, $route) use ($event) {
                    Notification::route($route, $account)
                        ->notify(new BounceNotification($event->mail));
                });
            });
        }
    }
}
