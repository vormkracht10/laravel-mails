<?php

namespace Vormkracht10\Mails\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Vormkracht10\Mails\Enums\Events\Postmark;
use Vormkracht10\Mails\Enums\Provider;
use Vormkracht10\Mails\Enums\WebhookEventType;
use Vormkracht10\Mails\Events\WebhookBounced;
use Vormkracht10\Mails\Events\WebhookClicked;
use Vormkracht10\Mails\Events\WebhookComplained;
use Vormkracht10\Mails\Events\WebhookDelivered;
use Vormkracht10\Mails\Events\WebhookEvent;
use Vormkracht10\Mails\Events\WebhookOpened;
use Vormkracht10\Mails\Facades\MailProvider;

class PostmarkWebhookController
{
    public function __invoke(Request $request): Response
    {
        $type = $request->string('RecordType');

        $type = $this->matchEvent($type);

        $uuid = MailProvider::driver('postmark')->getUuidFromPayload(
            $payload = $request->all(),
        );

        $timestamp = $this->getTimestamp($payload);

        WebhookEvent::dispatch(
            Provider::Postmark, $type, $payload, $uuid, $timestamp
        );

        return response(status: 202);
    }

    protected function matchEvent(string $event): WebhookEventType
    {
        return match ($event) {
            'Click' => WebhookEventType::CLICK,
            'SpamComplaint' => WebhookEventType::COMPLAINT,
            'Delivery' => WebhookEventType::DELIVERY,
            'Bounce' => WebhookEventType::BOUNCE,
            'Open' => WebhookEventType::OPEN,
        };
    }

    protected function getTimestamp(array $payload)
    {
        return $payload['DeliveredAt'] ?? $payload['BouncedAt'] ?? $payload['ReceivedAt'] ?? now();
    }
}
