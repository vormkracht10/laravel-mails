<?php

namespace Backstage\Mails\Listeners;

use Illuminate\Mail\Events\MessageSending;
use Backstage\Mails\Actions\AttachUuid;

class AttachMailLogUuid
{
    public function handle(MessageSending $event): void
    {
        (new AttachUuid)($event);
    }
}
