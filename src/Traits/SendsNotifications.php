<?php

namespace Vormkracht10\Mails\Traits;

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

            foreach ($accounts as $route) {
                Notifications::route($channel, $route)->notify(
                    $notification->on($channel),
                );
            }
        }
    }
}
