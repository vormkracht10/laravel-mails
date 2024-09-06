<?php

namespace Vormkracht10\Mails\Contracts;

use Vormkracht10\Mails\Models\Mail;

interface MailDriverContract
{
    public function getUuidFromPayload(array $payload): ?string;

    public function getMailFromPayload(array $payload): ?Mail;

    public function eventsMapping(): array;

    public function dataMapping(): array;

    public function record(Mail $mail, array $payload, $timestamp): void;

    public function logEvent(Mail $mail, array $payload, $timestamp): void;

    public function clicked(Mail $mail, array $timestamp): void;

    public function complained(Mail $mail, array $timestamp): void;

    public function delivered(Mail $mail, array $timestamp): void;

    public function bounced(Mail $mail, array $timestamp): void;

    public function opened(Mail $mail, array $timestamp): void;
}
