<?php

namespace Backstage\Mails\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Backstage\Mails\Jobs\ResendMailJob;
use Backstage\Mails\Models\Mail;

use function Laravel\Prompts\info;
use function Laravel\Prompts\text;

class ResendMailCommand extends Command implements PromptsForMissingInput
{
    public $signature = 'mail:resend {uuid} {to?*} {--cc=*} {--bcc=*}';

    public $description = 'Resend mail';

    public function handle(): int
    {
        $uuid = $this->argument('uuid');

        $mail = mail::where('uuid', $uuid)->first();

        if (is_null($mail)) {
            $this->components->error("Mail with uuid: \"{$uuid}\" does not exist");

            return Command::FAILURE;
        }

        info('For the next prompts you can input multiple email addresses by separating them with a comma.');

        [$to, $cc, $bcc] = $this->promptemailinputs($mail);

        ResendMailJob::dispatch($mail, $to, $cc, $bcc);

        info('All done');

        return self::SUCCESS;
    }

    protected function promptEmailInputs(Mail $mail): array
    {
        $to = implode(',', $this->argument('to')) ?: text(
            label: 'What email address do you want to send the mail to?',
            placeholder: 'test@example.com',
        );

        $cc = implode(',', $this->option('cc')) ?: text(
            label: 'What email address should be included in the cc?',
            placeholder: 'test@example.com',
        );

        $bcc = implode(',', $this->option('bcc')) ?: text(
            label: 'What email address should be included in the bcc?',
            placeholder: 'test@example.com',
        );

        foreach ([&$to, &$cc, &$bcc] as &$input) {
            $input = array_filter(array_map(fn ($s) => trim($s), explode(' ', str_replace([',', ';'], ' ', $input))));
        }

        return [$to ?: $mail->to, $cc ?: $mail->cc ?? [], $bcc ?: $mail->bcc ?? []];
    }

    protected function promptForMissingArgumentsUsing()
    {
        return ['uuid' => 'What is the UUID of the email you want to re-send?'];
    }
}
