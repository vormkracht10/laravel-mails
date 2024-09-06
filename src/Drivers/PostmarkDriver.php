<?php

namespace Vormkracht10\Mails\Drivers;

use Vormkracht10\Mails\Contracts\MailDriverContract;
use Vormkracht10\Mails\Enums\EventType;

class PostmarkDriver extends MailDriver implements MailDriverContract
{
    public function registerWebhooks($components): void
    {
        $trackingConfig = (array) config('mails.logging.tracking');

        $openTrigger = new WebhookConfigurationOpenTrigger((bool) $trackingConfig['opens'], false);
        $clickTrigger = new WebhookConfigurationClickTrigger((bool) $trackingConfig['clicks']);
        $deliveryTrigger = new WebhookConfigurationDeliveryTrigger((bool) $trackingConfig['deliveries']);
        $bounceTrigger = new WebhookConfigurationBounceTrigger((bool) $trackingConfig['bounces'], (bool) $trackingConfig['bounces']);
        $spamComplaintTrigger = new WebhookConfigurationSpamComplaintTrigger((bool) $trackingConfig['complaints'], (bool) $trackingConfig['complaints']);
        $triggers = new WebhookConfigurationTriggers($openTrigger, $clickTrigger, $deliveryTrigger, $bounceTrigger, $spamComplaintTrigger);

        $url = URL::signedRoute('mails.webhook', ['provider' => 'postmark']);

        $token = (string) config('services.postmark.token');
        $client = new PostmarkClient($token);

        $broadcastStream = collect($client->listMessageStreams()['messagestreams'] ?? []);

        if ($broadcastStream->where('ID', 'broadcast')->count() === 0) {
            $client->createMessageStream('broadcast', 'Broadcasts', 'Default Broadcast Stream');
        } else {
            $components->info('Broadcast stream already exists');
        }

        $outboundWebhooks = collect($client->getWebhookConfigurations('outbound')['webhooks'] ?? []);

        if ($outboundWebhooks->where('url', $url)->count() === 0) {
            $client->createWebhookConfiguration($url, null, null, null, $triggers);
        } else {
            $components->info('Outbound webhook already exists');
        }

        $broadcastWebhooks = collect($client->getWebhookConfigurations('broadcast')['webhooks'] ?? []);

        if ($broadcastWebhooks->where('url', $url)->count() === 0) {
            $client->createWebhookConfiguration($url, 'broadcast', null, null, $triggers);
        } else {
            $components->info('Broadcast webhook already exists');
        }
    }

    public function verifyWebhookSignature(array $payload): bool
    {
        return true;
    }

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
            EventType::CLICKED->value => ['RecordType' => 'Click'],
            EventType::COMPLAINED->value => ['RecordType' => 'SpamComplaint'],
            EventType::DELIVERED->value => ['RecordType' => 'Delivery'],
            EventType::HARD_BOUNCED->value => ['RecordType' => 'Bounce', 'Type' => 'HardBounce'],
            EventType::OPENED->value => ['RecordType' => 'Open'],
            EventType::SOFT_BOUNCED->value => ['RecordType' => 'Bounce', 'Type' => 'SoftBounce'],
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
