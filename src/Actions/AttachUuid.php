<?php

namespace Vormkracht10\Mails\Actions;

use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Str;
use Vormkracht10\Mails\Facades\MailProvider;
use Vormkracht10\Mails\Shared\AsAction;

class AttachUuid
{
    use AsAction;

    public function handle(MessageSending $event): void
    {
        $uuid = Str::uuid()->toString();

        $event->message->getHeaders()->addTextHeader(config('mails.headers.uuid'), $uuid);

        $event = MailProvider::with($event->data['mailer'])->attachUuidToMail($event, $uuid);
    }
}
