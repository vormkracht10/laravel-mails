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

            dd($this->mail->from, $this->formatMailAddresses($this->mail->from), $this->mail->reply_to, $this->formatMailAddresses($this->mail->reply_to));

            return $message
                ->subject($this->mail->subject ?? '')
                ->from($this->formatMailAddresses($this->mail->from))
                ->replyTo($this->formatMailAddresses($this->mail->reply_to ?? []))
                ->to($this->to ?? [])
                ->cc($this->cc ?? [])
                ->bcc($this->bcc ?? []);
        });
    }

    public function formatMailAddresses(string $email): array
    {
        return collect(
            json_decode(
                $email,
                JSON_OBJECT_AS_ARRAY
            )
        )
            ->map(fn($name, $email) => ['name' => $name, 'email' => $email])
            ->values();
    }
}