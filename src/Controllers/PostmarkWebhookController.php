<?php

namespace Vormkracht10\Mails\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Vormkracht10\Mails\Enums\Events\PostmarkEvent;
use Vormkracht10\Mails\Events\MailBounced;
use Vormkracht10\Mails\Events\MailClicked;
use Vormkracht10\Mails\Events\MailComplained;
use Vormkracht10\Mails\Events\MailDelivered;
use Vormkracht10\Mails\Events\MailOpened;

class PostmarkWebhookController
{
    public function __invoke(Request $request): JsonResponse
    {
        $payload = $request->input();

        if ((string) $payload->record_type == PostmarkEvent::CLICKED) {
            event(MailClicked::class);
        }

        if ((string) $payload->record_type == PostmarkEvent::COMPLAINED) {
            event(MailComplained::class);
        }

        if ((string) $payload->record_type == PostmarkEvent::DELIVERED) {
            event(MailDelivered::class);
        }

        if ((string) $payload->record_type == PostmarkEvent::HARD_BOUNCED) {
            event(MailBounced::class);
        }

        if ((string) $payload->record_type == PostmarkEvent::OPENED) {
            event(MailOpened::class);
        }

        return response()
            ->json('', 202);
    }
}
