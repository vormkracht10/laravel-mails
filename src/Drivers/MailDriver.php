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

    protected function getDataFromPayload(array $payload)
    {
        return collect($this->dataMapping())
            ->mapWithKeys(fn ($value, $key) => [$key => data_get($payload, $value)])
            ->filter()
            ->toArray();
    }

    protected function getEventFromPayload(array $payload)
    {
        foreach ($this->eventsMapping() as $event => $mapping) {
            if (collect($mapping)->every(fn ($value, $key) => data_get($payload, $key) === $value)) {
                return $event;
            }
        }
    }

    public function record(Mail $mail, array $payload, $timestamp = null): void
    {
        $timestamp ??= now();

        $method = strtolower($type->name);

        if (method_exists($this, $method)) {
            if (is_null($mail)) {
                return;
            }

            $this->{$method}($mail, $timestamp);

            $this->logEvent($mail, $type, $payload, $timestamp);
        }
    }

    public function logEvent(Mail $mail, WebhookEventType $event, array $payload, $timestamp): void
    {
        $mail->events()->create($this->getDataMapping($payload));
    }

    public function clicked($mail, $timestamp): void
    {
        $mail->update([
            'last_clicked_at' => $timestamp,
            'clicks' => $mail->clicks + 1,
        ]);
    }

    public function complained($mail, $timestamp): void
    {
        $mail->update([
            'complained_at' => $timestamp,
        ]);
    }

    public function delivered($mail, $timestamp): void
    {
        $mail->update([
            'delivered_at' => $timestamp,
        ]);
    }

    public function bounced($mail, $timestamp): void
    {
        $mail->update([
            'hard_bounced_at' => $timestamp,
        ]);
    }

    public function opened($mail, $timestamp): void
    {
        $mail->update([
            'last_opened_at' => $timestamp,
            'opens' => $mail->opens + 1,
        ]);
    }
}
