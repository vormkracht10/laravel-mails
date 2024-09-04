<?php

namespace Vormkracht10\Mails\Managers;

use Illuminate\Support\Manager;
use Vormkracht10\Mails\Drivers\MailgunDriver;
use Vormkracht10\Mails\Drivers\MailtrapDriver;
use Vormkracht10\Mails\Drivers\PostmarkDriver;

class MailProviderManager extends Manager
{
    public function with($driver)
    {
        return $this->driver($driver);
    }

    protected function createPostmarkDriver(): PostmarkDriver
    {
        return new PostmarkDriver;
    }

    protected function createMailgunDriver(): MailgunDriver
    {
        return new MailgunDriver;
    }

    protected function createMailtrapDriver(): MailtrapDriver
    {
        return new MailtrapDriver;
    }

    public function getDefaultDriver(): ?string
    {
        $defaultMailer = config('mail.default');
        $drivers = ['postmark', 'mailgun', 'mailtrap'];

        if (in_array($defaultMailer, $drivers)) {
            return $defaultMailer;
        }

        return null;
    }
}
