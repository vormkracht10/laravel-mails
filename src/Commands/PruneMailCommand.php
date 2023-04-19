<?php

namespace Vormkracht10\Mails\Commands;

use Illuminate\Console\Command;
use Vormkracht10\Mails\Models\Mail;

class PruneMailCommand extends Command
{
    public $signature = 'mail:prune';

    public $description = 'Prune logged sent emails';

    public function handle(): int
    {
        $this->call('model:prune', [
            '--model' => [Mail::class],
        ]);

        $this->comment('All done');

        return self::SUCCESS;
    }
}
