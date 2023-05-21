<?php

namespace Vormkracht10\Mails\Drivers;

use Vormkracht10\Mails\Contracts\MailDriverContract;
use Vormkracht10\Mails\Enums\Events\Mapping;
use Vormkracht10\Mails\Enums\Events\MappingPastTense;
use Vormkracht10\Mails\Enums\Events\Postmark;
use Vormkracht10\Mails\Events\MailEventLogged;
use Vormkracht10\Mails\Models\Mail;

class PostmarkDriver implements MailDriverContract
{
    protected $mailModel;

    protected $mailEventModel;

    public function __construct()
    {
        $this->mailModel = config('mails.models.mail');
        $this->mailEventModel = config('mails.models.event');
    }

    public function getUuidFromPayload(array $payload): string
    {
        return $payload['Metadata'][config('mails.headers.uuid')];
    }

    public function getMailFromPayload(array $payload): ?Mail
    {
        return (new $this->mailModel)
            ->firstWhere('uuid', $this->getUuidFromPayload($payload));
    }

    public function events(): array
    {
        return [
            Postmark::CLICK->value => Mapping::CLICK->value,
            Postmark::COMPLAINT->value => Mapping::COMPLAINT->value,
            Postmark::DELIVERY->value => Mapping::DELIVERY->value,
            Postmark::HARD_BOUNCE->value => Mapping::BOUNCE->value,
            Postmark::OPEN->value => Mapping::OPEN->value,
        ];
    }

    public function record(array $payload): void
    {
        $type = $payload['RecordType'];
        $method = $this->events()[$type] ?? null;

        if (method_exists($this, $method)) {
            $mail = $this->getMailFromPayload($payload);

            if (is_null($mail)) {
                return;
            }

            $this->{$method}($mail, $payload);

            $this->logEvent($mail, $method, $payload);
        }
    }

    public function logEvent(Mail $mail, string $method, array $payload): void
    {
        $mailEvent = $mail->events()->create([
            'type' => $method,
            // 'ip_address' => '',
            // 'hostname' => '',
            'payload' => $payload,
            'occurred_at' => $payload['DeliveredAt'] ?? $payload['BouncedAt'] ?? $payload['ReceivedAt'] ?? now(),
        ]);

        event(MailEventLogged::class, $mailEvent);

        $mailEventNamePastTense = ucfirst(MappingPastTense::fromName($method)->value);

        $eventClass = '\Vormkracht10\Mails\Events\Mail'.$mailEventNamePastTense;

        event($eventClass, $mailEvent);
    }

    public function click($mail, $payload): void
    {
        $mail->update([
            'last_clicked_at' => $payload['ReceivedAt'],
            'clicks' => $mail->clicks + 1,
        ]);
    }

    public function complaint($mail, $payload): void
    {
        $mail->update([
            'complained_at' => $payload['BouncedAt'],
        ]);
    }

    public function delivery($mail, $payload): void
    {
        $mail->update([
            'delivered_at' => $payload['DeliveredAt'],
        ]);
    }

    public function bounce($mail, $payload): void
    {
        $mail->update([
            'hard_bounced_at' => $payload['BouncedAt'],
        ]);
    }

    public function open($mail, $payload): void
    {
        $mail->update([
            'last_opened_at' => $payload['ReceivedAt'],
            'opens' => $mail->opens + 1,
        ]);
    }
}
