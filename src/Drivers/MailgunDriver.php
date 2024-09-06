<?php

namespace Vormkracht10\Mails\Drivers;

use Vormkracht10\Mails\Contracts\MailDriverContract;
use Vormkracht10\Mails\Enums\EventType;

class MailgunDriver extends MailDriver implements MailDriverContract
{
    public function registerWebhooks($components): void {}

    public function getUuidFromPayload(array $payload): ?string
    {
        return $payload['event-data']['message']['headers'][$this->uuidHeaderName] ??
            $payload['event-data']['message']['headers'][strtolower($this->uuidHeaderName)] ??
            $payload['event-data']['message']['headers'][strtoupper($this->uuidHeaderName)] ??
            null;
    }

    protected function getTimestampFromPayload(array $payload)
    {
        return $payload['DeliveredAt'] ?? $payload['BouncedAt'] ?? $payload['ReceivedAt'] ?? now();
    }

    public function eventMapping(): array
    {
        return [
            EventType::ACCEPTED->value => ['event-data.event' => 'accepted'],
            EventType::CLICKED->value => ['event-data.event' => 'clicked'],
            EventType::COMPLAINED->value => ['event-data.event' => 'complained'],
            EventType::DELIVERED->value => ['event-data.event' => 'delivered'],
            EventType::SOFT_BOUNCED->value => ['event-data.event' => 'failed', 'severity' => 'temporary'],
            EventType::HARD_BOUNCED->value => ['event-data.event' => 'failed', 'severity' => 'permanent'],
            EventType::OPENED->value => ['event-data.event' => 'opened'],
            EventType::UNSUBSCRIBED->value => ['event-data.event' => 'unsubscribed'],
        ];
    }

    public function dataMapping(): array
    {
        return [
            'ip_address' => 'Geo.IP',
            'platform' => 'Platform',
            'os' => 'OS.Family',
            'browser' => 'Client.Family',
            'user_agent' => 'UserAgent',
            'city' => 'City',
            'country_code' => 'Geo.CountryISOCode',
            'link' => 'OriginalLink',
            'tag' => 'Tag',
        ];
    }
}
