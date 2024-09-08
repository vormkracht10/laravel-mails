<?php

namespace Vormkracht10\Mails\Actions;

use Vormkracht10\Mails\Notifications\HighBounceRateNotification;
use Vormkracht10\Mails\Shared\AsAction;
use Vormkracht10\Mails\Traits\SendsNotifications;

class SendHighBounceRateNotifications
{
    use AsAction, SendsNotifications;

    /**
     * @param  float|int  $rate
     * @param  float|int  $threshold
     */
    public function handle($rate, $threshold): bool
    {
        if (! $channels = config('mails.events.bouncerate.notify')) {
            return false;
        }

        $notification = new HighBounceRateNotification($rate, $threshold);

        $this->send($notification, $channels);

        return true;
    }
}
