<?php

namespace Vormkracht10\Mails\Actions;

use Vormkracht10\Mails\Listeners\Concerns\SendsNotifications;
use Vormkracht10\Mails\Notifications\HighBounceRateNotification;
use Vormkracht10\Mails\Shared\AsAction;

class SendHighBounceRateNotifications
{
    use AsAction, SendsNotifications;

    /**
     * @param float|int $rate
     * @param float|int $threshold
     */
    public function handle($rate, $threshold): void
    {
        if (! $channels = config('mails.events.bouncerate.notify')) {
            return;
        }

        foreach ($channels as $channel) {
            $notification = new HighBounceRateNotification($rate, $threshold);

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
