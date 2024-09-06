<?php

namespace Vormkracht10\Mails\Drivers;

use Vormkracht10\Mails\Contracts\MailDriverContract;
use Vormkracht10\Mails\Enums\EventType;

class PostmarkDriver extends MailDriver implements MailDriverContract
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
            EventType::CLICKED => ['RecordType' => 'Click'],
            EventType::COMPLAINED => ['RecordType' => 'Complaint'],
            EventType::DELIVERED => ['RecordType' => 'Delivery'],
            EventType::HARD_BOUNCED => ['RecordType' => 'Bounce'],
            EventType::OPENED => ['RecordType' => 'Open'],
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
