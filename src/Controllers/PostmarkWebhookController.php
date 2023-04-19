<?php

namespace Vormkracht10\Mails\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Vormkracht10\Mails\Events\MailBounced;
use Vormkracht10\Mails\Events\MailClicked;
use Vormkracht10\Mails\Events\MailComplained;
use Vormkracht10\Mails\Events\MailDelivered;
use Vormkracht10\Mails\Events\MailOpened;
use Vormkracht10\Mails\Events\PostmarkEvent;

class PostmarkWebhookController
{
    public function __invoke(Request $request): Response
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

        return response('', 202)
            ->json();
    }
}
