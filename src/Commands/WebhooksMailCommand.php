<?php

namespace Vormkracht10\Mails\Commands;

use Illuminate\Console\Command;

class WebhooksMailCommand extends Command
{
    public $signature = 'mail:webhooks';

    public $description = 'Register webhooks for email providers';

    public function handle(): int
    {
        // TODO: register webhooks

        $this->comment('All done');

        return self::SUCCESS;
    }
}
