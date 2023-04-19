<?php

namespace Vormkracht10\Mails\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Vormkracht10\Mails\Enums\Events\Postmark;
use Vormkracht10\Mails\Events\MailBounced;
use Vormkracht10\Mails\Events\MailClicked;
use Vormkracht10\Mails\Events\MailComplained;
use Vormkracht10\Mails\Events\MailDelivered;
use Vormkracht10\Mails\Events\MailOpened;

class PostmarkWebhookController
{
    public function __invoke(Request $request): JsonResponse
    {
        if ($request->string('record_type') == Postmark::CLICKED) {
            event(MailClicked::class);
        }

        if ($request->string('record_type') == Postmark::COMPLAINED) {
            event(MailComplained::class);
        }

        if ($request->string('record_type') == Postmark::DELIVERED) {
            event(MailDelivered::class);
        }

        if ($request->string('record_type') == Postmark::HARD_BOUNCED) {
            event(MailBounced::class);
        }

        if ($request->string('record_type') == Postmark::OPENED) {
            event(MailOpened::class);
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
