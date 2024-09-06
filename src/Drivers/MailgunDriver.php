<?php

namespace Vormkracht10\Mails\Drivers;

use Vormkracht10\Mails\Contracts\MailDriverContract;
use Vormkracht10\Mails\Enums\EventType;

class MailgunDriver extends MailDriver implements MailDriverContract
{
    public function getUuidFromPayload(array $payload): ?string
    {
        return $payload['Metadata'][$this->uuidHeaderName] ??
            $payload['Metadata'][strtolower($this->uuidHeaderName)] ??
            $payload['Metadata'][strtoupper($this->uuidHeaderName)] ??
            null;
    }

    protected function getTimestampFromPayload(array $payload)
    {
        return $payload['DeliveredAt'] ?? $payload['BouncedAt'] ?? $payload['ReceivedAt'] ?? now();
    }

    public function eventMapping(): array
    {
        return [
            EventType::ACCEPTED => ['event-data.event' => 'accepted'],
            EventType::CLICKED => ['event-data.event' => 'clicked'],
            EventType::COMPLAINED => ['event-data.event' => 'complained'],
            EventType::DELIVERED => ['event-data.event' => 'delivered'],
            EventType::SOFT_BOUNCED => ['event-data.event' => 'failed', 'severity' => 'temporary'],
            EventType::HARD_BOUNCED => ['event-data.event' => 'failed', 'severity' => 'permanent'],
            EventType::OPENED => ['event-data.event' => 'opened'],
            EventType::UNSUBSCRIBED => ['event-data.event' => 'unsubscribed'],
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
