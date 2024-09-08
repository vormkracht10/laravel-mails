<?php

namespace Vormkracht10\Mails\Drivers;

use Vormkracht10\Mails\Contracts\MailDriverContract;
use Vormkracht10\Mails\Enums\EventType;

class MailgunDriver extends MailDriver implements MailDriverContract
{
    public function registerWebhooks($components): void {}

    public function verifyWebhookSignature(array $payload): bool
    {
        if (app()->runningUnitTests()) {
            return true;
        }

        if (empty($payload['signature']['timestamp']) || empty($payload['signature']['token']) || empty($payload['signature']['signature'])) {
            return false;
        }

        $hmac = hash_hmac('sha256', $payload['signature']['timestamp'].$payload['signature']['token'], config('services.mailgun.api_key'));

        if (function_exists('hash_equals')) {
            return hash_equals($hmac, $payload['signature']['signature']);
        }

        return $hmac === $payload['signature']['signature'];
    }

    public function getUuidFromPayload(array $payload): ?string
    {
        return $payload['event-data']['message']['headers'][$this->uuidHeaderName] ??
            $payload['event-data']['message']['headers'][strtolower($this->uuidHeaderName)] ??
            $payload['event-data']['message']['headers'][strtoupper($this->uuidHeaderName)] ??
            null;
    }

    protected function getTimestampFromPayload(array $payload): string
    {
        return $payload['event-data']['timestamp'];
    }

    public function eventMapping(): array
    {
        return [
            EventType::ACCEPTED->value => ['event-data.event' => 'accepted'],
            EventType::CLICKED->value => ['event-data.event' => 'clicked'],
            EventType::COMPLAINED->value => ['event-data.event' => 'complained'],
            EventType::DELIVERED->value => ['event-data.event' => 'delivered'],
            EventType::HARD_BOUNCED->value => ['event-data.event' => 'failed', 'event-data.severity' => 'permanent'],
            EventType::OPENED->value => ['event-data.event' => 'opened'],
            EventType::SOFT_BOUNCED->value => ['event-data.event' => 'failed', 'event-data.severity' => 'temporary'],
            EventType::UNSUBSCRIBED->value => ['event-data.event' => 'unsubscribed'],
        ];
    }

    public function dataMapping(): array
    {
        return [
            'ip_address' => 'ip',
            'platform' => 'client-info.device-type',
            'os' => 'client-info.client-os',
            'browser' => 'client-info.client-name',
            'user_agent' => 'client-info.user-agent',
            'country_code' => 'geolocation.region',
            'link' => 'event-data.url',
            'tag' => 'tags',
        ];
    }
}
