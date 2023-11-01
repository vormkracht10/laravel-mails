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
    public function send(Notification $notification, $on): void
    {
        $key = implode('.', ['mails', 'notifications', $on, 'to']);

        $accounts = array_wrap(
            config($key, []),
        );

        if (empty($accounts)) {
            return;
        }

        Notifications::route($on, $accounts)->notify(
            $notification->on($on),
        );
    }
}
