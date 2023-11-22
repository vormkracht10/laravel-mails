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
    public function send(Notification $notification, array $channels): void
    {
        foreach ($channels as $channel) {
            $key = implode('.', ['mails', 'notifications', $channel, 'to']);

            $accounts = array_wrap(
                config($key, []),
            );

            if (empty($accounts)) {
                return;
            }

            Notifications::route($channel, $accounts)->notify(
                $notification->on($channel),
            );
        }
    }
}
