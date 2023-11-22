<?php

namespace Vormkracht10\Mails\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Vormkracht10\Mails\Jobs\ResendMailJob;
use Vormkracht10\Mails\Models\Mail;

class ResendMailCommand extends Command implements PromptsForMissingInput
{
    public $signature = 'mail:resend {uuid} {to?} {cc?} {bcc?}';

    public $description = 'Resend mail';

    public function handle(): int
    {
        $mail = Mail::where('uuid', $this->argument('uuid'))->first();

        ResendMailJob::dispatch($mail,
            ...collect($this->argument())->only(['to', 'cc', 'bcc'])->map(fn ($n) => $n ?? []),
        );

        $this->comment('All done');

        return self::SUCCESS;
    }

    protected function promptForMissingArgumentsUsing()
    {
        return ['uuid' => 'What is the UUID of the email you want to re-send?'];
    }
}
