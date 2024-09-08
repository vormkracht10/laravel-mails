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

            $replyTo = $this->formatMailAddresses($this->mail->reply_to ?? [])[0] ?? null;
            $from = $this->formatMailAddresses($this->mail->from)[0] ?? null;

            $message->subject($this->mail->subject ?? '');

            if ($from) {
                $message->from($from['email'], $from['name'] ?? null);
            }

            if ($replyTo) {
                $message->replyTo($replyTo['email'], $replyTo['name'] ?? null);
            }

            return $message
                ->to($this->to ?? [])
                ->cc($this->cc ?? [])
                ->bcc($this->bcc ?? []);
        });
    }

    public function formatMailAddresses(string|array $email): array
    {
        if (is_string($email)) {
            $email = json_decode($email, true) ?? [];
        }

        return collect($email)
            ->map(fn($value, $key) => is_numeric($key) ? ['email' => $value] : ['name' => $value, 'email' => $key])
            ->values()
            ->toArray();
    }
}