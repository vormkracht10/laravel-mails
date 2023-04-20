<?php

namespace Vormkracht10\Mails\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Vormkracht10\Mails\Enums\Events\Postmark;
use Vormkracht10\Mails\Events\MailBounced;
use Vormkracht10\Mails\Events\MailClicked;
use Vormkracht10\Mails\Events\MailComplained;
use Vormkracht10\Mails\Events\MailDelivered;
use Vormkracht10\Mails\Events\MailEvent;
use Vormkracht10\Mails\Events\MailOpened;

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
            Postmark::CLICKED->value => MailClicked::class,
            Postmark::COMPLAINED->value => MailComplained::class,
            Postmark::DELIVERED->value => MailDelivered::class,
            Postmark::HARD_BOUNCED->value => MailBounced::class,
            Postmark::OPENED->value => MailOpened::class,
        ];
    }
}
