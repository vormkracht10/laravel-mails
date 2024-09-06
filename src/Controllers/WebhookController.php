<?php

namespace Vormkracht10\Mails\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Vormkracht10\Mails\Enums\Provider;
use Vormkracht10\Mails\Events\MailEvent;

class PostmarkWebhookController
{
    public function __invoke(Request $request, string $driver): Response
    {
        MailEvent::dispatch(
            Provider::{ucfirst($driver)}, $request->except('signature')
        );

        return response('Event processed.', status: 202);
    }
}
