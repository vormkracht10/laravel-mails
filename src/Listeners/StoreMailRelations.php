<?php

namespace Vormkracht10\Mails\Listeners;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Events\MessageSending;
use Symfony\Component\Mime\Email;

class StoreMailRelations
{
    public function handle(MessageSending $event): void
    {
        $message = $event->message;

        if (! $this->shouldAssociateModels($message)) {
            return;
        }

        $models = $this->getAssociatedModels($message);

        $mail = $this->getMailModel($message);

        foreach ($models as $identifier) {
            [$model, $keyName, $id] = $identifier;

            $model = $model::query()->where($keyName, $id)->limit(1)->first();

            $model->associateMail($mail);
        }
    }

    protected function shouldAssociateModels(Email $message): bool
    {
        return $message->getHeaders()->has(
            $this->getAssociatedHeaderName(),
        );
    }

    protected function getAssociatedModels(Email $message): array|false
    {
        $encrypted = $this->getHeaderBody(
            $message,
            $this->getAssociatedHeaderName(),
        );

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

    protected function getAssociatedHeaderName(): string
    {
        return config('mails.headers.associate', 'X-Mails-Associated-Models');
    }
}
