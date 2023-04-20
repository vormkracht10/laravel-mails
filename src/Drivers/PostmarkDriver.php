<?php

namespace Vormkracht10\Mails\Drivers;

use Illuminate\Support\Facades\DB;
use Vormkracht10\Mails\Enums\Events\Postmark;

class PostmarkDriver
{
    protected $mailModel;

    protected $mailEventModel;

    public function __construct()
    {
        $this->mailModel = config('mails.models.mail');
        $this->mailEventModel = config('mails.models.event');
    }

    public function getUuidFromPayload($event)
    {
        return $event->payload['Metadata'][config('mails.headers.uuid')];
    }

    public function getMailFromEvent($event)
    {
        return (new $model)
            ->firstWhere('uuid', $this->getUuidFromPayload($event));
    }

    public function events()
    {
        return [
            Postmark::CLICKED->value => 'clicked',
            Postmark::COMPLAINED->value => 'complained',
            Postmark::DELIVERED->value => 'delivered',
            Postmark::HARD_BOUNCED->value => 'bounced',
            Postmark::OPENED->value => 'opened',
        ];
    }

    public function record($event): void
    {
        $type = $event->payload['RecordType'];
        $method = $this->events()[$type] ?? null;

        if (method_exists($this, $method)) {
            $this->{$method}($event);
        }
    }

    public function clicked($event): void
    {
        $this->getMailFromEvent($event)
            ?->update([
                'last_clicked_at' => $event->payload['ReceivedAt'],
                'clicks' => DB::raw('clicks + 1'),
            ]);
    }

    public function complained($event): void
    {
        $this->getMailFromEvent($event)
            ?->update([
                'complained_at' => $event->payload['BouncedAt'],
            ]);
    }

    public function delivered($event): void
    {
        $this->getMailFromEvent($event)
            ?->update([
                'delivered_at' => $event->payload['ReceivedAt'],
            ]);
    }

    public function bounced($event): void
    {
        $this->getMailFromEvent($event)
            ?->update([
                'hard_bounced_at' => $event->payload['BouncedAt'],
            ]);
    }

    public function opened($event): void
    {
        $this->getMailFromEvent($event)
            ?->update([
                'last_opened_at' => $event->payload['ReceivedAt'],
                'opens' => DB::raw('opens + 1'),
            ]);
    }
}
