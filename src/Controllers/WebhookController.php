<?php

namespace Backstage\Mails\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Backstage\Mails\Enums\Provider;
use Backstage\Mails\Events\MailEvent;
use Backstage\Mails\Facades\MailProvider;

class WebhookController
{
    public function __invoke(Request $request, string $provider): Response
    {
        if (! in_array($provider, array_column(Provider::cases(), 'value'))) {
            return response('Unknown provider.', status: 400);
        }

        if (! MailProvider::with($provider)->verifyWebhookSignature($request->all())) {
            return response('Invalid signature.', status: 400);
        }

        MailEvent::dispatch($provider, $request->except('signature'));

        return response('Event processed.', status: 202);
    }
}
