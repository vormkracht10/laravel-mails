<?php

namespace Vormkracht10\Mails\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Vormkracht10\Mails\Jobs\ResendMailJob;
use Vormkracht10\Mails\Models\Mail;

class ResendMailCommand extends Command implements PromptsForMissingInput
{
    public $signature = 'mail:resend {uuid} {to?*} {--cc=*} {--bcc=*}';

    public $description = 'Resend mail';

    public function handle(): int
    {
        $uuid = $this->argument('uuid');

        $mail = Mail::where('uuid', $uuid)->first();

        if (is_null($mail)) {
            $this->components->error("Mail with UUID: \"{$uuid}\" does not exist");

            return Command::FAILURE;
        }

        ResendMailJob::dispatch($mail,
            $this->argument('to') ?: $mail->to,
            $this->option('cc') ?: $mail->cc ?? [],
            $this->option('bcc') ?: $mail->bcc ?? [],
        );

        $this->comment('All done');

        return self::SUCCESS;
    }

    protected function promptForMissingArgumentsUsing()
    {
        return ['uuid' => 'What is the UUID of the email you want to re-send?'];
    }
}
