<?php

namespace Vormkracht10\Mails\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Vormkracht10\Mails\Enums\Provider;
use Vormkracht10\Mails\Events\MailEvent;
use Vormkracht10\Mails\Facades\MailProvider;

class WebhookController
{
    public function __invoke(Request $request, string $driver): Response
    {
        if (array_key_exists($driver, array_column(Provider::cases(), 'value'))) {
            return response('Unknown provider.', status: 400);
        }

        if (MailProvider::with($driver)->verifyWebhookSignature($request->all())) {
            return response('Invalid signature.', status: 400);
        }

        MailEvent::dispatch(
            $driver, $request->except('signature')
        );

        return response('Event processed.', status: 202);
    }
}
