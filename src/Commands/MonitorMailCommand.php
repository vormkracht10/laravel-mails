<?php

namespace Backstage\Mails\Commands;

use Illuminate\Console\Command;
use Backstage\Mails\Models\Mail;

class MonitorMailCommand extends Command
{
    public $signature = 'mail:monitor';

    public $description = 'Monitor for sent mails';

    public function handle(): int
    {
        if (null !== $bounceRateTreshold = config('mails.events.bouncerate.treshold')) {
            if ($this->getBounceRate() >= $bounceRateTreshold) {
                // TODO: notify
            }
        }

        if (null !== $deliveryRateTreshold = config('mails.events.deliveryrate.treshold')) {

            if ($this->getDeliveryRate() <= $deliveryRateTreshold) {
                // TODO: notify
            }
        }

        return self::SUCCESS;
    }

    public function getBounceRate(): float
    {
        $bounces = Mail::whereNotNull('soft_bounced_at')->orWhereNotNull('hard_bounced_at')->count();
        $total = Mail::count();

        return ($bounces / $total) * 100;
    }

    public function getDeliveryRate(): float
    {
        $deliveries = Mail::whereNotNull('delivered_at')->count();
        $total = Mail::count();

        return ($deliveries / $total) * 100;
    }
}
