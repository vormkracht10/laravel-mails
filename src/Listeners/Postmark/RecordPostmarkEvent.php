<?php

namespace Vormkracht10\Mails\Listeners\Postmark;

use Illuminate\Support\Facades\DB;
use Vormkracht10\Mails\Enums\Events\Postmark;

class RecordPostmarkEvent
{
    public function handle($event): void
    {
        $model = config('mails.models.mail');

        $type = $event->payload['RecordType'];
        $column = $this->databaseColumns()[$type];
        $timestamp = $this->timestamps()[$type];
        $value = $event->payload[$timestamp];

        if ($type == Postmark::CLICKED->value) {
            $stats = ['clicks' => DB::raw('opens + 1')];
        }

        if ($type == Postmark::OPENED->value) {
            $stats = ['opens' => DB::raw('opens + 1')];
        }

        $update = array_merge([
            $column => $value,
        ], $stats ?? []);

        logger()->debugger($update);

        (new $model)
            ->firstWhere('uuid', $event->payload['Metadata'][config('mails.headers.uuid')])
            ?->update($update);
    }

    public function timestamps(): array
    {
        return [
            Postmark::CLICKED->value => 'ReceivedAt',
            Postmark::COMPLAINED->value => 'BouncedAt',
            Postmark::DELIVERED->value => 'DeliveredAt',
            Postmark::HARD_BOUNCED->value => 'BouncedAt',
            Postmark::OPENED->value => 'ReceivedAt',
        ];
    }

    public function databaseColumns(): array
    {
        return [
            Postmark::CLICKED->value => 'last_clicked_at',
            Postmark::COMPLAINED->value => 'complained_at',
            Postmark::DELIVERED->value => 'delivered_at',
            Postmark::HARD_BOUNCED->value => 'hard_bounced_at',
            Postmark::OPENED->value => 'last_opened_at',
        ];
    }
}
