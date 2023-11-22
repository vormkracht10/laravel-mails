<?php

namespace Vormkracht10\Mails\Jobs;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Message;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Vormkracht10\Mails\Models\Mail;

class ResendMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, InteractsWithSockets;

    public function __construct(
        private readonly Mail $mail,
        private array $to = [],
        private array $cc = [],
        private array $bcc = [],
    ) {
        $this->checkFields($this->mail);
    }

    public function handle(): void
    {
        \Illuminate\Support\Facades\Mail::send([], callback: fn (Message $mail) => $mail
            ->replyTo($this->mail->reply_to ?? [])
            ->subject($this->mail->subject ?? '')
            ->to($this->to ?? [])
            ->cc($this->cc ?? [])
            ->bcc($this->bcc ?? [])
            && is_null($this->mail->html)
            ? $mail->text($this->mail->text ?? '')
            : $mail->html($this->mail->html)
        );
    }

    protected function checkFields(Mail $mail)
    {
        if (! empty($this->to)) {
            return;
        }

        [$this->to, $this->cc, $this->bcc] = array_values(
            collect($mail->only(['to', 'cc', 'bcc']))->map(fn($n) => $n ?? [])->toArray(),
        );
    }
}
