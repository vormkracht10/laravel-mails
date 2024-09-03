<?php

namespace Vormkracht10\Mails\Controllers;

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

        if (is_null($type)) {
            return response('Event type unknown.', status: 400);
        }

        $uuid = MailProvider::driver('postmark')->getUuidFromPayload(
            $payload = $request->except('signature'),
        );

        if (is_null($uuid)) {
            return response('Mail UUID not found.', status: 202);
        }

        $timestamp = $this->getTimestamp($payload);

        WebhookEvent::dispatch(
            Provider::Postmark, $type, $payload, $uuid, $timestamp
        );

        WebhookEvent::dispatch(
            Provider::Mailgun, $type, $request->all(), null, null
        );

        return response('Event processed.', status: 202);
    }

    protected function matchEvent(string $event): ?WebhookEventType
    {
        return match ($event) {
            'clicked' => WebhookEventType::CLICK,
            'complained' => WebhookEventType::COMPLAINT,
            'delivered' => WebhookEventType::DELIVERY,
            'opened' => WebhookEventType::OPEN,
            'permanent_fail', 'temporary_fail' => WebhookEventType::BOUNCE,
            default => null,
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

    protected function getTimestamp(array $payload)
    {
        return $payload['DeliveredAt'] ?? $payload['BouncedAt'] ?? $payload['ReceivedAt'] ?? now();
    }
}
