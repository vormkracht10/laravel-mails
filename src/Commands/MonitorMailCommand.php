<?php

namespace Vormkracht10\Mails\Commands;

use Illuminate\Console\Command;

class MonitorMailCommand extends Command
{
    public $signature = 'mail:monitor';

    public $description = 'Monitor for sent mails';

    public function handle(): int
    {
        // TODO: notify when bounce rate is high

        return self::SUCCESS;
    }
}
