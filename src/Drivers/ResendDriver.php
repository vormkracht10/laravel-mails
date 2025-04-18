<?php

namespace Vormkracht10\Mails\Drivers;

use Illuminate\Mail\Events\MessageSending;
use Vormkracht10\Mails\Contracts\MailDriverContract;
use Vormkracht10\Mails\Enums\EventType;
use Vormkracht10\Mails\Exceptions\LaravelResendException;
use Vormkracht10\Mails\Models\Mail;

class ResendDriver extends MailDriver implements MailDriverContract
{
    protected array $trackingConfig;

    public function __construct()
    {
        parent::__construct();
        $this->trackingConfig = (array) config('mails.logging.tracking');
    }

    /**
     * This method only checks that the Laravel Resend package is installed
     * and configured as this package sets up webhook functionality
     */
    public function registerWebhooks($components): void
    {
        /**
         * Configuration for webhooks are only available in dashboard
         */
        if (! class_exists('Resend\Laravel\ResendServiceProvider')) {
            throw new LaravelResendException('Unable to find Laravel Resend Package');
        }

        if (empty(config('resend.webhook.secret'))) {
            throw new LaravelResendException('Invalid Resend webhook secret');
        }
    }

    public function verifyWebhookSignature(array $payload): bool
    {
        /**
         * Using webhook created by laravel resend package.
         */
        return false;
    }

    public function attachUuidToMail(MessageSending $event, string $uuid): MessageSending
    {
        $event->message->getHeaders()->addTextHeader('X-Resend-Variables', json_encode([config('mails.headers.uuid') => $uuid]));

        return $event;
    }

    public function getUuidFromPayload(array $payload): ?string
    {
        return $payload['data']['email_id'];
    }

    protected function getTimestampFromPayload(array $payload): string
    {
        return $payload['data']['created_at'] ?? now();
    }

    public function eventMapping(): array
    {

        return [
            EventType::ACCEPTED->value => ['type' => 'email.sent'],
            EventType::CLICKED->value => ['type' => 'email.clicked'],
            EventType::COMPLAINED->value => ['type' => 'email.complained'],
            EventType::DELIVERED->value => ['type' => 'email.delivered'],
            EventType::HARD_BOUNCED->value => ['type' => 'email.bounced'],
            EventType::OPENED->value => ['type' => 'email.opened'],
            EventType::SOFT_BOUNCED->value => ['type' => 'email.delivery_delayed'],
        ];
    }

    public function dataMapping(): array
    {
        return [
            'ip_address' => 'data.click.ipAddress',
            'link' => 'data.click.link',
            'user_agent' => 'data.click.userAgent',
        ];
    }

    public function clicked(Mail $mail, string $timestamp): void
    {
        if (! empty($this->trackingConfig['clicks']) && $this->trackingConfig['clicks']) {
            parent::clicked($mail, $timestamp);
        }
    }

    public function complained(Mail $mail, string $timestamp): void
    {
        if (! empty($this->trackingConfig['complaints']) && $this->trackingConfig['complaints']) {
            parent::complained($mail, $timestamp);
        }
    }

    public function delivered(Mail $mail, string $timestamp): void
    {
        if (! empty($this->trackingConfig['deliveries']) && $this->trackingConfig['deliveries']) {
            parent::delivered($mail, $timestamp);
        }
    }

    public function hardBounced(Mail $mail, string $timestamp): void
    {
        if (! empty($this->trackingConfig['bounces']) && $this->trackingConfig['bounces']) {
            parent::hardBounced($mail, $timestamp);
        }
    }

    public function softBounced(Mail $mail, string $timestamp): void
    {
        if (! empty($this->trackingConfig['bounces']) && $this->trackingConfig['bounces']) {
            parent::softBounced($mail, $timestamp);
        }
    }

    public function opened(Mail $mail, string $timestamp): void
    {
        if (! empty($this->trackingConfig['opened']) && $this->trackingConfig['opened']) {
            parent::softBounced($mail, $timestamp);
        }
    }
}
