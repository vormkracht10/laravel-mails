<?php

namespace Vormkracht10\Mails\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Vormkracht10\Mails\Enums\Provider;
use Vormkracht10\Mails\Enums\WebhookEventType;
use Vormkracht10\Mails\Events\WebhookEvent;
use Vormkracht10\Mails\Facades\MailProvider;

class PostmarkWebhookController
{
    public function __invoke(Request $request): Response
    {
        $type = $request->string('RecordType');

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

        return response('Event processed.', status: 202);
    }

    protected function matchEvent(string $event): ?WebhookEventType
    {
        return match ($event) {
            'Click' => WebhookEventType::CLICK,
            'SpamComplaint' => WebhookEventType::COMPLAINT,
            'Delivery' => WebhookEventType::DELIVERY,
            'Bounce' => WebhookEventType::BOUNCE,
            'Open' => WebhookEventType::OPEN,
            default => null,
        };
    }

    protected function getTimestamp(array $payload)
    {
        return $payload['DeliveredAt'] ?? $payload['BouncedAt'] ?? $payload['ReceivedAt'] ?? now();
    }
}
