<?php

namespace Vormkracht10\Mails\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Vormkracht10\Mails\Enums\Events\Mailgun;
use Vormkracht10\Mails\Events\WebhookBounced;
use Vormkracht10\Mails\Events\WebhookClicked;
use Vormkracht10\Mails\Events\WebhookComplained;
use Vormkracht10\Mails\Events\WebhookDelivered;
use Vormkracht10\Mails\Events\WebhookOpened;

class MailgunWebhookController
{
    public function __invoke(Request $request): JsonResponse
    {
        $event = $this->events()[$request->input('event')] ?? null;

        if (! is_null($event)) {
            event($event, [
                'provider' => 'mailgun',
                'payload' => $request->input(),
            ]);
        }

        return response()
            ->json('', 202);
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
