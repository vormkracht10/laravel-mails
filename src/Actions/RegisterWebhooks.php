<?php

namespace Vormkracht10\Mails\Actions;

use Illuminate\Console\Concerns\InteractsWithIO;
use Illuminate\Support\Facades\URL;
use Postmark\Models\Webhooks\WebhookConfigurationBounceTrigger;
use Postmark\Models\Webhooks\WebhookConfigurationClickTrigger;
use Postmark\Models\Webhooks\WebhookConfigurationDeliveryTrigger;
use Postmark\Models\Webhooks\WebhookConfigurationOpenTrigger;
use Postmark\Models\Webhooks\WebhookConfigurationSpamComplaintTrigger;
use Postmark\Models\Webhooks\WebhookConfigurationTriggers;
use Postmark\PostmarkClient;
use Vormkracht10\Mails\Shared\AsAction;

class RegisterWebhooks
{
    use AsAction, InteractsWithIO;

    public function handle()
    {
        $trackingConfig = (array) config('mails.logging.tracking');

        $openTrigger = new WebhookConfigurationOpenTrigger((bool) $trackingConfig['opens'], false);
        $clickTrigger = new WebhookConfigurationClickTrigger((bool) $trackingConfig['clicks']);
        $deliveryTrigger = new WebhookConfigurationDeliveryTrigger((bool) $trackingConfig['deliveries']);
        $bounceTrigger = new WebhookConfigurationBounceTrigger((bool) $trackingConfig['bounces'], (bool) $trackingConfig['bounces']);
        $spamComplaintTrigger = new WebhookConfigurationSpamComplaintTrigger((bool) $trackingConfig['complaints'], (bool) $trackingConfig['complaints']);
        $triggers = new WebhookConfigurationTriggers($openTrigger, $clickTrigger, $deliveryTrigger, $bounceTrigger, $spamComplaintTrigger);

        $url = URL::signedRoute('mails.webhooks.postmark');

        $token = (string) config('services.postmark.token');
        $client = new PostmarkClient($token);

        $broadcastStream = collect($client->listMessageStreams()['messagestreams'] ?? []);

        if ($broadcastStream->where('ID', 'broadcast')->count() === 0) {
            $client->createMessageStream('broadcast', 'Broadcasts', 'Default Broadcast Stream');
        } else {
            $this->components->info('Broadcast stream already exists');
        }

        $outboundWebhooks = collect($client->getWebhookConfigurations('outbound')['webhooks'] ?? []);

        if ($outboundWebhooks->where('url', $url)->count() === 0) {
            $client->createWebhookConfiguration($url, null, null, null, $triggers);
        } else {
            $this->components->info('Outbound webhook already exists');
        }

        $broadcastWebhooks = collect($client->getWebhookConfigurations('broadcast')['webhooks'] ?? []);

        if ($broadcastWebhooks->where('url', $url)->count() === 0) {
            $client->createWebhookConfiguration($url, 'broadcast', null, null, $triggers);
        } else {
            $this->components->info('Broadcast webhook already exists');
        }
    }
}
