<?php

namespace Vormkracht10\Mails\Drivers;

use Vormkracht10\Mails\Models\Mail;

class MailDriver
{
    protected string $mailModel;

    protected string $mailEventModel;

    protected string $uuidHeaderName;

    public function __construct()
    {
        $this->mailModel = config('mails.models.mail');
        $this->mailEventModel = config('mails.models.event');
        $this->uuidHeaderName = config('mails.headers.uuid');
    }

    public function getMailFromPayload(array $payload): ?Mail
    {
        return $this->mailModel::query()
            ->firstWhere('uuid', $this->getUuidFromPayload($payload));
    }

    public function getDataFromPayload(array $payload): array
    {
        return collect($this->dataMapping())
            ->mapWithKeys(fn ($value, $key) => [$key => data_get($payload, $value)])
            ->filter()
            ->merge([
                'type' => $this->getEventFromPayload($payload),
                'timestamp' => $this->getTimestampFromPayload($payload),
            ])
            ->toArray();
    }

    public function getEventFromPayload(array $payload)
    {
        foreach ($this->eventsMapping() as $event => $mapping) {
            if (collect($mapping)->every(fn ($value, $key) => data_get($payload, $key) === $value)) {
                return $event;
            }
        }
    }

    public function logMailEvent($payload): void
    {
        $mail = $this->getMailFromPayload($payload);

        $method = strtolower($this->getEventFromPayload($payload));

        if (method_exists($this, $method)) {
            if (is_null($mail)) {
                return;
            }

            // log mail event
            $mail->events()->create($this->getDataMapping($payload));

            // update mail record with timestamp
            $this->{$method}($mail, $this->getTimestampFromPayload($payload));
        }
    }

    public function accepted(Mail $mail, string $timestamp): void
    {
        $mail->update([
            'last_opened_at' => $timestamp,
            'opens' => $mail->opens + 1,
        ]);
    }

    public function clicked(Mail $mail, string $timestamp): void
    {
        $mail->update([
            'last_clicked_at' => $timestamp,
            'clicks' => $mail->clicks + 1,
        ]);
    }

    public function complained(Mail $mail, string $timestamp): void
    {
        $mail->update([
            'complained_at' => $timestamp,
        ]);
    }

    public function delivered(Mail $mail, string $timestamp): void
    {
        $mail->update([
            'delivered_at' => $timestamp,
        ]);
    }

    public function hardBounced(Mail $mail, string $timestamp): void
    {
        $mail->update([
            'hard_bounced_at' => $timestamp,
        ]);
    }

    public function opened(Mail $mail, string $timestamp): void
    {
        $mail->update([
            'last_opened_at' => $timestamp,
            'opens' => $mail->opens + 1,
        ]);
    }

    public function softBounced(Mail $mail, string $timestamp): void
    {
        $mail->update([
            'soft_bounced_at' => $timestamp,
        ]);
    }

    public function unsubscribed(Mail $mail, string $timestamp): void
    {
        $mail->update([
            'last_opened_at' => $timestamp,
            'opens' => $mail->opens + 1,
        ]);
    }
}
