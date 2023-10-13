<?php

namespace Vormkracht10\Mails\Listeners;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Queue\SerializesAndRestoresModelIdentifiers;
use Symfony\Component\Mime\Email;

class StoreMailRelations implements ShouldQueue
{
    use SerializesAndRestoresModelIdentifiers, Queueable;

    public function handle(MessageSending $event): void
    {
        $message = $event->message;

        $models = $this->getAssociatedModels($message);

        $mail = $this->getMailModel($message);

        foreach ($models as $identifier) {
            [$model, $keyName, $id] = $identifier;

            $model = $model::query()->where($keyName, $id)->limit(1)->first();

            $model->associateMail($mail);
        }
    }

    protected function getAssociatedModels(Email $message): array
    {
        $encrypted = $this->getHeaderBody($message, config('mails.headers.associate'));

        $payload = decrypt($encrypted);

        return json_decode($payload, true);
    }

    protected function getMailModel(Email $message): Model
    {
        $uuid = $this->getHeaderBody($message, config('mails.headers.uuid'));

        $model = config('mails.models.mail');

        return $model::query()->where('uuid', $uuid)->limit(1)->first();
    }

    protected function getHeaderBody(Email $message, string $header): mixed
    {
        return $message->getHeaders()->getHeaderBody($header);
    }
}
