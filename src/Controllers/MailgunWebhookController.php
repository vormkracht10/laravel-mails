<?php

namespace Vormkracht10\Mails\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Vormkracht10\Mails\Enums\Events\Mailgun;
use Vormkracht10\Mails\Events\MailBounced;
use Vormkracht10\Mails\Events\MailClicked;
use Vormkracht10\Mails\Events\MailComplained;
use Vormkracht10\Mails\Events\MailDelivered;
use Vormkracht10\Mails\Events\MailOpened;

class MailgunWebhookController
{
    public function __invoke(Request $request): JsonResponse
    {
        $event = $this->events()[$request->input('event')] ?? null;

        if (! is_null($event)) {
            event($event, ['payload' => $request->input()]);
        }

        return response()
            ->json('', 202);
    }

    public function events(): array
    {
        return [
            Mailgun::CLICKED->value => MailClicked::class,
            Mailgun::COMPLAINED->value => MailComplained::class,
            Mailgun::DELIVERED->value => MailDelivered::class,
            Mailgun::HARD_BOUNCED->value => MailBounced::class,
            Mailgun::OPENED->value => MailOpened::class,
        ];
    }
}
