<?php

namespace Vormkracht10\Mails\Commands;

use Illuminate\Console\Command;

class MonitorMailCommand extends Command
{
    public $signature = 'mail:monitor';

    public $description = 'Monitor for sent mails';

    public function handle(): int
    {
        // TODO: check for high bounce rate
        // TODO: check for mails that are sent in the app, but not delivered

        return self::SUCCESS;
    }
}
