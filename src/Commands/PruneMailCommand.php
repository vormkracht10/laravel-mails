<?php

namespace Vormkracht10\Mails\Commands;

use Illuminate\Console\Command;

class PruneMailCommand extends Command
{
    public $signature = 'mail:prune';

    public $description = 'Prune logged sent emails';

    public function handle(): int
    {
        // TODO: prune mail

        $this->call('model:prune', [
            '--model' => [Mail::class],
        ]);

        $this->comment('All done');

        return self::SUCCESS;
    }
}
