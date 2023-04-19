<?php

namespace Vormkracht10\Mails\Actions;

use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Str;

class AttachUuid
{
    public function execute(MessageSending $event)
    {
        if ($event->message->getHeaders()->has(config('mails.headers.uuid'))) {
            return;
        }

        $event->message->getHeaders()->addTextHeader(config('mails.headers.uuid'), Str::uuid()->toString());
    }
}
