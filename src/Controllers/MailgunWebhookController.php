<?php

namespace Vormkracht10\Mails\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Vormkracht10\Mails\Enums\Events\Mailgun;
use Vormkracht10\Mails\Enums\Provider;
use Vormkracht10\Mails\Enums\WebhookEventType;
use Vormkracht10\Mails\Events\WebhookBounced;
use Vormkracht10\Mails\Events\WebhookClicked;
use Vormkracht10\Mails\Events\WebhookComplained;
use Vormkracht10\Mails\Events\WebhookDelivered;
use Vormkracht10\Mails\Events\WebhookEvent;
use Vormkracht10\Mails\Events\WebhookOpened;
use Vormkracht10\Mails\Facades\MailProvider;

class MailgunWebhookController
{
    public function __invoke(Request $request): Response
    {
        $type = $request->input('event');

        $type = $this->matchEvent($type);

        WebhookEvent::dispatch(
            Provider::Mailgun, $type, $request->all(), null, null
        );

        return response(status: 202);
    }

    protected function matchEvent(string $event): WebhookEventType
    {
        return match ($event) {
            'clicked' => WebhookEventType::CLICK,
            'complained' => WebhookEventType::COMPLAINT,
            'delivered' => WebhookEventType::DELIVERY,
            'opened' => WebhookEventType::OPEN,
            'permanent_fail', 'temporary_fail' => WebhookEventType::BOUNCE,
        };
    }

    public function events(): array
    {
        return [
            Mailgun::CLICK->value => WebhookClicked::class,
            Mailgun::COMPLAINT->value => WebhookComplained::class,
            Mailgun::DELIVERY->value => WebhookDelivered::class,
            Mailgun::HARD_BOUNCE->value => WebhookBounced::class,
            Mailgun::OPEN->value => WebhookOpened::class,
        ];
    }
}
