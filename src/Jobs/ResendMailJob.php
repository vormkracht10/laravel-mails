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
            $message->attachData($attachment->fileData, $attachment->filename, ['mime' => $attachment->mime]);
        }

        return $this;
    }

    private function setMessageRecipients(Message $message): self
    {
        $message->subject($this->mail->subject ?? '')
            ->from($this->getFirstAddress($this->mail->from))
            ->to($this->to);

        if ($this->mail->cc) {
            $message->cc($this->mail->cc);
        }

        if ($this->mail->bcc) {
            $message->bcc($this->mail->bcc);
        }

        if ($this->mail->reply_to) {
            $message->replyTo($this->getFirstAddress($this->mail->reply_to));
        }

        return $this;
    }

    private function getFirstAddress(string $jsonAddresses): string
    {
        $addresses = json_decode($jsonAddresses, true);

        return array_key_first($addresses);
    }
}
