<?php

namespace Vormkracht10\Mails\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Vormkracht10\Mails\Enums\Events\Postmark;
use Vormkracht10\Mails\Events\MailEvent;
use Vormkracht10\Mails\Events\WebhookBounced;
use Vormkracht10\Mails\Events\WebhookClicked;
use Vormkracht10\Mails\Events\WebhookComplained;
use Vormkracht10\Mails\Events\WebhookDelivered;
use Vormkracht10\Mails\Events\WebhookOpened;

class PostmarkWebhookController
{
    public function __invoke(Request $request): JsonResponse
    {
        $event = $this->events()[$request->input('RecordType')] ?? null;

        if (! is_null($event)) {
            event(MailEvent::class, [
                'provider' => 'postmark',
                'payload' => $request->input(),
            ]);

            event($event, [
                'provider' => 'postmark',
                'payload' => $request->input(),
            ]);
        }

        return response()
            ->json('', 202);
    }

    public function events(): array
    {
        return [
            Postmark::CLICK->value => WebhookClicked::class,
            Postmark::COMPLAINT->value => WebhookComplained::class,
            Postmark::DELIVERY->value => WebhookDelivered::class,
            Postmark::HARD_BOUNCE->value => WebhookBounced::class,
            Postmark::OPEN->value => WebhookOpened::class,
        ];
    }
}
