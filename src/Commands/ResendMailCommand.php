<?php

namespace Vormkracht10\Mails\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Vormkracht10\Mails\Jobs\ResendMailJob;
use Vormkracht10\Mails\Models\Mail;

use function Laravel\Prompts\info;
use function Laravel\Prompts\text;

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

        info('For the next prompts you can input multiple email addresses by separating them with a comma.');

        [$to, $cc, $bcc] = $this->promptEmailInputs($mail);

        ResendMailJob::dispatch($mail, $to, $cc, $bcc);

        $this->comment('All done');

        return self::SUCCESS;
    }

    protected function promptEmailInputs(Mail $mail): array
    {
        $to = $this->argument('to') ?: explode(',', text(
            label: 'What email address do you want to send the mail to?',
            placeholder: 'test@example.com',
        ));

        $cc = $this->option('cc') ?: explode(',', text(
            label: 'What email address should be included in the cc?',
            placeholder: 'test@example.com',
        ));

        $bcc = $this->option('bcc') ?: explode(',', text(
            label: 'What email address should be included in the bcc?',
            placeholder: 'test@example.com',
        ));

        $inputs = [
            [&$to, $mail->to],
            [&$cc, $mail->cc ?? []],
            [&$bcc, $mail->bcc ?? []],
        ];

        foreach ($inputs as [&$array, $default]) {
            $array = array_filter(array_map(fn ($email) => trim($email), $array)) ?: $default;
        }

        return [$to, $cc, $bcc];
    }

    protected function promptForMissingArgumentsUsing()
    {
        return ['uuid' => 'What is the UUID of the email you want to re-send?'];
    }
}
