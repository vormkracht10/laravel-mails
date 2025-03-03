<?php

namespace Vormkracht10\Mails\Drivers;

use Illuminate\Http\Client\Response;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Vormkracht10\Mails\Contracts\MailDriverContract;
use Vormkracht10\Mails\Enums\EventType;
use Vormkracht10\Mails\Enums\Provider;

class PostmarkDriver extends MailDriver implements MailDriverContract
{
    public function registerWebhooks($components): void
    {
        $trackingConfig = (array) config('mails.logging.tracking');

        $triggers = [
            'Open' => [
                'Enabled' => (bool) $trackingConfig['opens'],
                'PostFirstOpenOnly' => false,
            ],
            'Click' => [
                'Enabled' => (bool) $trackingConfig['clicks'],
            ],
            'Delivery' => [
                'Enabled' => (bool) $trackingConfig['deliveries'],
            ],
            'Bounce' => [
                'Enabled' => (bool) $trackingConfig['bounces'],
                'IncludeContent' => (bool) $trackingConfig['bounces'],
            ],
            'SpamComplaint' => [
                'Enabled' => (bool) $trackingConfig['complaints'],
                'IncludeContent' => (bool) $trackingConfig['complaints'],
            ],
            'SubscriptionChange' => [
                'Enabled' => (bool) $trackingConfig['unsubscribes'],
            ],
        ];

        $webhookUrl = URL::signedRoute('mails.webhook', ['provider' => Provider::POSTMARK]);

        $token = (string) config('services.postmark.token');

        $headers = [
            'Accept' => 'application/json',
            'X-Postmark-Server-Token' => $token,
        ];

        $broadcastStream = collect(Http::withHeaders($headers)->get('https://api.postmarkapp.com/message-streams')['MessageStreams'] ?? []);

        if ($broadcastStream->where('ID', 'broadcast')->count() === 0) {
            Http::withHeaders($headers)->post('https://api.postmarkapp.com/message-streams', [
                'ID' => 'broadcast',
                'Name' => 'Broadcasts',
                'Description' => 'Default Broadcast Stream',
            ]);
        } else {
            $components->info('Broadcast stream already exists');
        }

        $outboundWebhooks = collect(Http::withHeaders($headers)->get('https://api.postmarkapp.com/webhooks?MessageStream=outbound')['Webhooks'] ?? []);

        if ($outboundWebhooks->where('Url', $webhookUrl)->count() === 0) {
            $response = Http::withHeaders($headers)->post('https://api.postmarkapp.com/webhooks?MessageStream=outbound', [
                'Url' => $webhookUrl,
                'Triggers' => $triggers,
            ]);

            if ($response->ok()) {
                $components->info('Created Postmark webhook for outbound stream');
            } else {
                $components->error('Failed to create Postmark webhook for outbound stream');
            }
        } else {
            $components->info('Outbound webhook already exists');
        }

        $broadcastWebhooks = collect(Http::withHeaders($headers)->get('https://api.postmarkapp.com/webhooks?MessageStream=broadcast')['Webhooks'] ?? []);

        if ($broadcastWebhooks->where('Url', $webhookUrl)->count() === 0) {
            $response = Http::withHeaders($headers)->post('https://api.postmarkapp.com/webhooks?MessageStream=broadcast', [
                'Url' => $webhookUrl,
                'MessageStream' => 'broadcast',
                'Triggers' => $triggers,
            ]);

            if ($response->ok()) {
                $components->info('Created Postmark webhook for broadcast stream');
            } else {
                $components->error('Failed to create Postmark webhook for broadcast stream');
            }
        } else {
            $components->info('Broadcast webhook already exists');
        }
    }

    public function verifyWebhookSignature(array $payload): bool
    {
        return true;
    }

    public function attachUuidToMail(MessageSending $event, string $uuid): MessageSending
    {
        $event->message->getHeaders()->addTextHeader('X-PM-Metadata-'.config('mails.headers.uuid'), $uuid);

        return $event;
    }

    public function getUuidFromPayload(array $payload): ?string
    {
        return $payload['Metadata'][$this->uuidHeaderName] ??
            $payload['Metadata'][strtolower($this->uuidHeaderName)] ??
            $payload['Metadata'][strtoupper($this->uuidHeaderName)] ??
            null;
    }

    protected function getTimestampFromPayload(array $payload): string
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
            EventType::UNSUBSCRIBED->value => ['RecordType' => 'SubscriptionChange'],
        ];
    }

    public function dataMapping(): array
    {
        return [
            'browser' => 'Client.Family',
            'city' => 'City',
            'country_code' => 'Geo.CountryISOCode',
            'ip_address' => 'Geo.IP',
            'link' => 'OriginalLink',
            'os' => 'OS.Family',
            'platform' => 'Platform',
            'tag' => 'Tag',
            'user_agent' => 'UserAgent',
        ];
    }

    public function unsuppressEmailAddress(string $address, $stream_id): Response
    {
        $client = Http::asJson()
            ->withHeaders([
                'X-Postmark-Server-Token' => config('services.postmark.token'),
            ])
            ->baseUrl('https://api.postmarkapp.com/');

        return $client->post('message-streams/'.$stream_id.'/suppressions/delete', [
            'Suppressions' => [['emailAddress' => $address]],
        ]);
    }
}
