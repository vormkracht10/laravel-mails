<?php

namespace Vormkracht10\LaravelMails\Commands;

use Illuminate\Console\Command;

class LaravelMailsCommand extends Command
{
    public $signature = 'laravel-mails';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
