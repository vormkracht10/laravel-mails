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

        $event = MailProvider::with($this->getProvider($event))->attachUuidToMail($event, $uuid);
    }

    public function getProvider(MessageSending $event): string
    {
        return config('mail.mailers.'.$event->data['mailer'].'.transport') ?? $event->data['mailer'];
    }
}
