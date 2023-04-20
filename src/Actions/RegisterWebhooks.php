<?php

namespace Vormkracht10\Mails\Actions;

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
    use AsAction;

    public function handle()
    {
        $trackingConfig = (array) config('mails.logging.tracking');

        $openTrigger = new WebhookConfigurationOpenTrigger($trackingConfig['opens'], $trackingConfig['opens']);
        $clickTrigger = new WebhookConfigurationClickTrigger($trackingConfig['clicks']);
        $deliveryTrigger = new WebhookConfigurationDeliveryTrigger($trackingConfig['deliveries']);
        $bounceTrigger = new WebhookConfigurationBounceTrigger($trackingConfig['bounces'], $trackingConfig['bounces']);
        $spamComplaintTrigger = new WebhookConfigurationSpamComplaintTrigger($trackingConfig['complaints'], $trackingConfig['complaints']);
        $triggers = new WebhookConfigurationTriggers($openTrigger, $clickTrigger, $deliveryTrigger, $bounceTrigger, $spamComplaintTrigger);

        $url = URL::signedRoute('mails.webhooks.postmark');

        $client = new PostmarkClient(config('services.postmark.token'));

        $broadcastStream = collect($client->listMessageStreams()['messagestreams'] ?? []);

        if ($broadcastStream->where('ID', 'broadcast')->count() === 0) {
            $client->createMessageStream('broadcast', 'Broadcasts', 'Default Broadcast Stream');
        }

        $outboundWebhooks = collect($client->getWebhookConfigurations('outbound')['webhooks'] ?? []);

        if ($outboundWebhooks->where('url', $url)->count() === 0) {

            $client->createWebhookConfiguration($url, null, null, null, $triggers);
        } else {
            // ... Webhook for outbound messages already exists
        }

        $broadcastWebhooks = collect($client->getWebhookConfigurations('broadcast')['webhooks'] ?? []);

        if ($broadcastWebhooks->where('url', $url)->count() === 0) {
            $client->createWebhookConfiguration($url, 'broadcast', null, null, $triggers);
        } else {
            // ... Webhook for broadcast messages already exists
        }
    }
}
