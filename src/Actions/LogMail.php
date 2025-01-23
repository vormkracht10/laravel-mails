<?php

namespace Vormkracht10\Mails\Actions;

use Vormkracht10\Mails\Shared\AsAction;
use Symfony\Component\Mime\Address;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Contracts\Mail\Mailer;

class LogMail
{
    use AsAction;

    public function handle(MessageSending|MessageSent $event, $mailer): mixed
    {
        if (! config('mails.logging.enabled')) {
            return null;
        }

        $mail = $this->newMailModelInstance();

        if ($event instanceof MessageSending) {
            $mail->fill($this->getOnlyConfiguredAttributes($event, $mailer));
            $mail->save();

            $this->collectAttachments($mail, $event->message->getAttachments());
        }

        if ($event instanceof MessageSent) {
            $mail = $mail->firstWhere('uuid', $this->getCustomUuid($event));

            $mail->update($this->getOnlyConfiguredAttributes($event, $mailer));
        }

        return null;
    }

    /**
     * @return \Vormkracht10\Mails\Models\Mail
     */
    public function newMailModelInstance()
    {
        $model = config('mails.models.mail');

        return new $model;
    }

    public function getOnlyConfiguredAttributes(MessageSending|MessageSent $event, Mailer $mailer): array
    {
        return collect($this->getDefaultLogAttributes($event))
            ->only($this->getConfiguredAttributes())
            ->merge($this->getMandatoryAttributes($event, $mailer))
            ->toArray();
    }

    public function getConfiguredAttributes(): array
    {
        return (array) config('mails.logging.attributes');
    }

    public function getDefaultLogAttributes(MessageSending|MessageSent $event): array
    {
        return [
            'subject' => $event->message->getSubject(),
            'from' => $this->getAddressesValue($event->message->getFrom()),
            'reply_to' => $this->getAddressesValue($event->message->getReplyTo()),
            'to' => $this->getAddressesValue($event->message->getTo()),
            'cc' => $this->getAddressesValue($event->message->getCc()),
            'bcc' => $this->getAddressesValue($event->message->getBcc()),
            'html' => $event->message->getHtmlBody(),
            'text' => $event->message->getTextBody(),
        ];
    }

    protected function getMailerName(Mailer $mailer)
    {
        $class = $mailer;

        $reflection = new \ReflectionClass($class);
        $property = $reflection->getProperty('name');
        $property->setAccessible(true);

        $name = $property->getValue($class);

        return $name;
    }

    protected function getStreamId(MessageSending|MessageSent $event, string $driver)
    {
        if ($driver !== 'postmark') {
            return null;
        }

        if (! $event->message->getHeaders()->has('x-pm-metadata-x-mails-uuid')) {
            return null;
        }

        $headerValue = $event->message->getHeaders()->get('x-pm-metadata-x-mails-uuid');

        return $headerValue->getValue();
    }

    public function getMandatoryAttributes(MessageSending|MessageSent $event, Mailer $mailer): array
    {
        $driver = $this->getMailerName($mailer);

        return [
            'uuid' => $this->getCustomUuid($event),
            // 'mail_class' => $this->getMailClassHeaderValue($event),
            'sent_at' => $event instanceof MessageSent ? now() : null,
            'driver' => $driver,
            'stream_id' => $this->getStreamId($event, $this->getMailerName($mailer)),
        ];
    }

    protected function getCustomUuid(MessageSending|MessageSent $event): ?string
    {
        if (! $event->message->getHeaders()->has(config('mails.headers.uuid'))) {
            return null;
        }

        $headerValue = $event->message->getHeaders()->get(config('mails.headers.uuid'));

        if (is_null($headerValue)) {
            return null;
        }

        return $headerValue->getBodyAsString();
    }

    protected function getAddressesValue(array $address): ?Collection
    {
        $addresses = collect($address)
            ->flatMap(fn (Address $address) => [$address->getAddress() => $address->getName() === '' ? null : $address->getName()]);

        return $addresses->count() > 0 ? $addresses : null;
    }

    public function collectAttachments($mail, $attachments): void
    {
        collect($attachments)->each(function ($attachment) use ($mail) {
            $attachmentModel = $mail->attachments()->create([
                'disk' => config('mails.logging.attachments.disk'),
                'uuid' => $attachment->getContentId(),
                'filename' => $attachment->getFilename(),
                'mime' => $attachment->getContentType(),
                'inline' => ! str_contains($attachment->getFilename() ?: '', '.'), // workaround, because disposition is a private property
                'size' => strlen($attachment->getBody()),
            ]);

            $this->saveAttachment($attachmentModel, $attachment);
        });
    }

    public function saveAttachment($attachmentModel, $attachment): void
    {
        Storage::disk($attachmentModel->disk)
            ->put($attachmentModel->storagePath, $attachment->getBody(), 'private');
    }
}
