<?php

namespace Vormkracht10\Mails\Commands;

use Illuminate\Console\Command;

class ResendMailCommand extends Command
{
    public $signature = 'mail:resend {uuid} {to} {cc} {bcc}';

    public $description = 'Resend mail';

    public function handle(): int
    {
        // TODO: resend mail

        $this->comment('All done');

        return self::SUCCESS;
    }
}
