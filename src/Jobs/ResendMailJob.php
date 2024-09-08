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
        Mail::send([], callback: function (Message $message) {
            $message->html($this->mail->html ?? '')
                ->text($this->mail->text ?? '');

            foreach ($this->mail->attachments as $attachment) {
                $message->attachData($attachment->fileData, $attachment->filename, ['mime' => $attachment->mime]);
            }
 
            return $message
                ->subject($this->mail->subject ?? '')
                ->from(address: array_key_first($this->mail->from))
                ->replyTo(address: array_key_first($this->mail->reply_to))
                ->to($this->to ?? [])
                ->cc($this->cc ?? [])
                ->bcc($this->bcc ?? []);
        });
    }
}