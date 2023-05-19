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
            Mailgun::CLICKED->value => WebhookClicked::class,
            Mailgun::COMPLAINED->value => WebhookComplained::class,
            Mailgun::DELIVERED->value => WebhookDelivered::class,
            Mailgun::HARD_BOUNCED->value => WebhookBounced::class,
            Mailgun::OPENED->value => WebhookOpened::class,
        ];
    }
}
