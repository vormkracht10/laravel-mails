<?php

namespace Vormkracht10\Mails\Listeners;

use Vormkracht10\Mails\Enums\WebhookEventType;
use Vormkracht10\Mails\Events\WebhookBounced;
use Vormkracht10\Mails\Events\WebhookClicked;
use Vormkracht10\Mails\Events\WebhookComplained;
use Vormkracht10\Mails\Events\WebhookDelivered;
use Vormkracht10\Mails\Events\WebhookEvent;
use Vormkracht10\Mails\Events\WebhookOpened;

class RelayWebhookEventsListener
{
    public function handle(WebhookEvent $event)
    {
        $cls = match ($event->type) {
            WebhookEventType::CLICK => WebhookClicked::class,
            WebhookEventType::COMPLAINT => WebhookComplained::class,
            WebhookEventType::DELIVERY => WebhookDelivered::class,
            WebhookEventType::BOUNCE => WebhookBounced::class,
            WebhookEventType::OPEN => WebhookOpened::class,
        };

        event(new $cls(
            $event->provider,
            $event->payload,
        ));
    }
}
