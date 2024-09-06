<?php

namespace Vormkracht10\Mails\Contracts;

use Vormkracht10\Mails\Models\Mail;

interface MailDriverContract
{
    public function getUuidFromPayload(array $payload): ?string;

    public function getMailFromPayload(array $payload): ?Mail;

    public function getDataFromPayload(array $payload): array;

    public function eventMapping(): array;

    public function dataMapping(): array;

    public function logMailEvent(array $payload): void;

    public function accepted(Mail $mail, string $timestamp): void;

    public function clicked(Mail $mail, string $timestamp): void;

    public function complained(Mail $mail, string $timestamp): void;

    public function delivered(Mail $mail, string $timestamp): void;

    public function hardBounced(Mail $mail, string $timestamp): void;

    public function opened(Mail $mail, string $timestamp): void;

    public function softBounced(Mail $mail, string $timestamp): void;

    public function unsubscribed(Mail $mail, string $timestamp): void;
}
