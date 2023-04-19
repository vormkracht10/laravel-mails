<?php

namespace Vormkracht10\Mails\Actions;

use Illuminate\Mail\Events\MessageSending;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Collection;
use Symfony\Component\Mime\Address;

class LogMail
{
    public function execute(MessageSending|MessageSent $event): void
    {
        $mail = $this->newMailModelInstance();

        if ($event instanceof MessageSending) {
            $mail->fill($this->getOnlyConfiguredAttributes($event));
            $mail->save();
        }

        if ($event instanceof MessageSent) {
            $mail->firstWhere('uuid', $this->getCustomUuid($event))
                ?->update($this->getOnlyConfiguredAttributes($event));
        }
    }

    public function newMailModelInstance()
    {
        $model = config('mails.models.mail');

        return new $model;
    }

    public function getOnlyConfiguredAttributes(MessageSending|MessageSent $event): array
    {
        return collect($this->getDefaultLogAttributes($event))
            ->only($this->getConfiguredAttributes())
            ->merge($this->getMandatoryAttributes($event))
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

    public function getMandatoryAttributes(MessageSending|MessageSent $event): array
    {
        return [
            'uuid' => $this->getCustomUuid($event),
            // 'mail_class' => $this->getMailClassHeaderValue($event),
            'sent_at' => $event instanceof MessageSent ? now() : null,
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
}
