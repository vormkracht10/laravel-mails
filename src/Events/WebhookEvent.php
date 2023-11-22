<?php

namespace Vormkracht10\Mails\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Vormkracht10\Mails\Enums\Provider;
use Vormkracht10\Mails\Enums\WebhookEventType;
use Vormkracht10\Mails\Models\Mail;

class WebhookEvent
{
    use Dispatchable;

    protected ?Mail $mail;

    public function __construct(
        public Provider $provider,
        public WebhookEventType $type,
        public array $payload = [],
        protected ?string $mailUuid = null,
        public $timestamp = null,
    ) {
    }

    public function mail(): ?Mail
    {
        if (! isset($this->mail)) {
            $this->mail = Mail::query()
                ->where('uuid', $this->mailUuid)
                ->limit(1)
                ->first();
        }

        return $this->mail;
    }
}
