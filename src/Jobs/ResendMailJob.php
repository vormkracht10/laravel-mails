<?php

namespace Vormkracht10\Mails\Jobs;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Message;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Vormkracht10\Mails\Models\Mail as Mailable;

class ResendMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, InteractsWithSockets, Queueable, SerializesModels;

    /**
     * @param  non-empty-array<int, string>  $to
     */
    public function __construct(
        private readonly Mailable $mail,
        private array $to,
        private array $cc = [],
        private array $bcc = [],
    ) {
        //
    }

    public function handle(): void
    {
        Mail::send([], callback: function (Message $mail) {
            match (isset($this->mail->html)) {
                true => $mail->html($this->mail->html),
                false => $mail->text($this->mail->text ?? ''),
            };

            return $mail
                ->replyTo($this->mail->reply_to ?? [])
                ->subject($this->mail->subject ?? '')
                ->to($this->to)
                ->cc($this->cc)
                ->bcc($this->bcc);
        });
    }
}
