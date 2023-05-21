<?php

namespace Vormkracht10\Mails\Contracts;

use Vormkracht10\Mails\Models\Mail;

interface MailDriverContract
{
    public function getUuidFromPayload(array $payload): string;

    public function getMailFromPayload(array $payload): ?Mail;

    public function events(): array;

    public function record(array $payload): void;

    public function logEvent(Mail $mail, string $event, array $payload): void;

    public function click(Mail $mail, array $payload): void;

    public function complaint(Mail $mail, array $payload): void;

    public function delivery(Mail $mail, array $payload): void;

    public function bounce(Mail $mail, array $payload): void;

    public function open(Mail $mail, array $payload): void;
}
