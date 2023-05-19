<?php

namespace Vormkracht10\Mails\Drivers;

use Vormkracht10\Mails\Enums\Events\Postmark;
use Vormkracht10\Mails\Events\MailEventLogged;
use Vormkracht10\Mails\Models\Mail;

class PostmarkDriver
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

    public function getMailFromPayload(array $payload): Mail
    {
        return (new $this->mailModel)
            ->firstWhere('uuid', $this->getUuidFromPayload($payload));
    }

    public function events(): array
    {
        return [
            Postmark::CLICKED->value => 'clicked',
            Postmark::COMPLAINED->value => 'complained',
            Postmark::DELIVERED->value => 'delivered',
            Postmark::HARD_BOUNCED->value => 'bounced',
            Postmark::OPENED->value => 'opened',
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

        $eventClass = '\Vormkracht10\Mails\Events\Mail'.ucfirst($method);

        event(new $eventClass, $mailEvent);
    }

    public function clicked($mail, $payload): void
    {
        $mail->update([
            'last_clicked_at' => $payload['ReceivedAt'],
            'clicks' => $mail->clicks + 1,
        ]);
    }

    public function complained($mail, $payload): void
    {
        $mail->update([
            'complained_at' => $payload['BouncedAt'],
        ]);
    }

    public function delivered($mail, $payload): void
    {
        $mail->update([
            'delivered_at' => $payload['DeliveredAt'],
        ]);
    }

    public function bounced($mail, $payload): void
    {
        $mail->update([
            'hard_bounced_at' => $payload['BouncedAt'],
        ]);
    }

    public function opened($mail, $payload): void
    {
        $mail->update([
            'last_opened_at' => $payload['ReceivedAt'],
            'opens' => $mail->opens + 1,
        ]);
    }
}
