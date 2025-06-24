<?php

namespace Backstage\Mails\Actions;

use Backstage\Mails\Notifications\HighBounceRateNotification;
use Backstage\Mails\Shared\AsAction;
use Backstage\Mails\Traits\SendsNotifications;

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
