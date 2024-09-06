<?php

namespace Vormkracht10\Mails\Actions;

use Vormkracht10\Mails\Jobs\ResendMailJob;
use Vormkracht10\Mails\Models\Mail as Mailable;
use Vormkracht10\Mails\Shared\AsAction;

class ResendMail
{
    use AsAction;

    public function handle(Mailable $mail, array $to = [], array $cc = [], array $bcc = [])
    {
        ResendMailJob::dispatch($mail, $to, $cc, $bcc);
    }
}
