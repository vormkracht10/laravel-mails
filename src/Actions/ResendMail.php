<?php

namespace Backstage\Mails\Actions;

use Backstage\Mails\Jobs\ResendMailJob;
use Backstage\Mails\Models\Mail as Mailable;
use Backstage\Mails\Shared\AsAction;

class ResendMail
{
    use AsAction;

    public function handle(Mailable $mail, array $to = [], array $cc = [], array $bcc = [], array $replyTo = [])
    {
        ResendMailJob::dispatch($mail, $to, $cc, $bcc, $replyTo);
    }
}
