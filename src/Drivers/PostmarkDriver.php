<?php

namespace Vormkracht10\Mails\Drivers;

use Vormkracht10\Mails\Enums\Events\Postmark;

class PostmarkDriver
{
    protected $mailModel;

    protected $mailEventModel;

    public function __construct()
    {
        $this->mailModel = config('mails.models.mail');
        $this->mailEventModel = config('mails.models.event');
    }

    public function getUuidFromPayload($payload)
    {
        return $payload['Metadata'][config('mails.headers.uuid')];
    }

    public function getMailFromPayload($payload)
    {
        return (new $this->mailModel)
            ->firstWhere('uuid', $this->getUuidFromPayload($payload));
    }

    public function events()
    {
        return [
            Postmark::CLICKED->value => 'clicked',
            Postmark::COMPLAINED->value => 'complained',
            Postmark::DELIVERED->value => 'delivered',
            Postmark::HARD_BOUNCED->value => 'bounced',
            Postmark::OPENED->value => 'opened',
        ];
    }

    public function record($payload): void
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

    public function logEvent($mail, $method, $payload): void
    {
        $mail->events()->create([
            'type' => $method,
            // 'ip_address' => '',
            // 'hostname' => '',
            'payload' => $payload,
            'occurred_at' => $payload['DeliveredAt'] ?? $payload['BouncedAt'] ?? $payload['ReceivedAt'] ?? now(),
        ]);
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
