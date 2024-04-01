<?php

namespace Vormkracht10\Mails\Contracts;

use Vormkracht10\Mails\Enums\WebhookEventType;
use Vormkracht10\Mails\Models\Mail;

interface MailDriverContract
{
    public function getUuidFromPayload(array $payload): ?string;

    public function getMailFromPayload(array $payload): ?Mail;

    public function events(): array;

    public function record(Mail $mail, WebhookEventType $type, array $payload, $timestamp): void;

    public function logEvent(Mail $mail, WebhookEventType $event, array $payload, $timestamp): void;

    public function click(Mail $mail, array $timestamp): void;

    public function complaint(Mail $mail, array $timestamp): void;

    public function delivery(Mail $mail, array $timestamp): void;

    public function bounce(Mail $mail, array $timestamp): void;

    public function open(Mail $mail, array $timestamp): void;
}
