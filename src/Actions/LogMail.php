<?php

namespace Vormkracht10\Mails\Actions;

use Illuminate\Mail\Events\MessageSending;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Collection;
use Symfony\Component\Mime\Address;

class LogMail
{
    public function execute(MessageSending|MessageSent $event)
    {

    }

    public function getOnlyConfiguredAttributes(MessageSent|MessageSending $event): array
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

    public function getDefaultLogAttributes(MessageSent|MessageSending $event): array
    {
        $attributes = [
            'subject' => $event->message->getSubject(),
            'from' => $this->getAddressesValue($event->message->getFrom()),
            'reply_to' => $this->getAddressesValue($event->message->getReplyTo()),
            'to' => $this->getAddressesValue($event->message->getTo()),
            'cc' => $this->getAddressesValue($event->message->getCc()),
            'bcc' => $this->getAddressesValue($event->message->getBcc()),
            'html' => $event->message->getHtmlBody(),
            'text' => $event->message->getTextBody(),
        ];

        if ($event instanceof MessageSent) {
            $attributes['sent_at'] = now();
        }

        return $attributes;
    }

    public function getMandatoryAttributes(MessageSent|MessageSending $event): array
    {
        return [
            'uuid' => $this->getCustomUuid($event),
            // 'mail_class' => $this->getMailClassHeaderValue($event),
            'sent_at' => now(),
        ];
    }

    protected function getCustomUuid(MessageSending|MessageSent $event): ?string
    {
        if (! $event->message->getHeaders()->has((string) config('mails.headers.uuid'))) {
            return null;
        }

        $headerValue = $event->message->getHeaders()->get((string) config('mails.headers.uuid'));

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
