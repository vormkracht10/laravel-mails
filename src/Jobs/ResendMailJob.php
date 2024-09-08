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
        private array $replyTo = []
    ) {
        //
    }

    public function handle(): void
    {
        Mail::send([], [], function (Message $message) {
            $this->setMessageContent($message)
                ->setMessageRecipients($message);
        });
    }

    private function setMessageContent(Message $message): self
    {
        $message->html($this->mail->html ?? '')
            ->text($this->mail->text ?? '');

        foreach ($this->mail->attachments as $attachment) {
            $message->attachData(
                $attachment->file_data ?? $attachment->fileData ?? '',
                $attachment->file_name ?? $attachment->filename ?? '',
                ['mime' => $attachment->mime_type ?? $attachment->mime ?? '']
            );
        }

        return $this;
    }

    private function setMessageRecipients(Message $message): self
    {
        $message->subject($this->mail->subject ?? '')
            ->from($this->getFirstAddress($this->mail->from))
            ->to($this->to);

        if ($this->mail->cc || $this->cc) {
            $message->cc($this->mail->cc ?? $this->cc);
        }

        if ($this->mail->bcc || $this->bcc) {
            $message->bcc($this->mail->bcc ?? $this->bcc);
        }

        if ($this->mail->reply_to || $this->replyTo) {
            $message->replyTo($this->getFirstAddress($this->mail->reply_to ?? $this->replyTo));
        }

        return $this;
    }

    private function getFirstAddress(string $jsonAddresses): string
    {
        $addresses = json_decode($jsonAddresses, true);

        return array_key_first($addresses);
    }
}
