<?php

namespace Vormkracht10\Mails\Commands;

use Illuminate\Console\Command;
use Vormkracht10\Mails\Actions\RegisterWebhooks;

class WebhooksMailCommand extends Command
{
    public $signature = 'mail:webhooks';

    public $description = 'Register webhooks for email providers';

    public function handle(): int
    {
        (new RegisterWebhooks)($this->components);

        $this->comment('All done');

        return self::SUCCESS;
    }
}
