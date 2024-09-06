<?php

namespace Vormkracht10\Mails\Actions;

use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Str;
use Vormkracht10\Mails\Shared\AsAction;

class AttachUuid
{
    use AsAction;

    public function handle(MessageSending $event)
    {
        if ($event->message->getHeaders()->has(config('mails.headers.uuid'))) {
            return;
        }

        $uuid = Str::uuid()->toString();

        $event->message->getHeaders()->addTextHeader(config('mails.headers.uuid'), $uuid);

        // specifically for Postmark
        $event->message->getHeaders()->addTextHeader('X-PM-Metadata-'.config('mails.headers.uuid'), $uuid);
    }
}
