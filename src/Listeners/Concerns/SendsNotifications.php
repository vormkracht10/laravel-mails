<?php

namespace Vormkracht10\Mails\Listeners\Concerns;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Notification as Notifications;
use Vormkracht10\Mails\Notifications\Concerns\HasDynamicDrivers;

trait SendsNotifications
{
    /**
     * @param  HasDynamicDrivers & Notification  $notification
     */
    public function send(Notification $notification, $on, array $to): void
    {
        Notifications::route($on, $to)->notify(
            $notification->on($on),
        );
    }
}
