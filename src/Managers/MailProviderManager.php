<?php

namespace Vormkracht10\Mails\Managers;

use Illuminate\Support\Manager;
use Vormkracht10\Mails\Drivers\MailgunDriver;
use Vormkracht10\Mails\Drivers\PostmarkDriver;
use Vormkracht10\Mails\Drivers\SesDriver;

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

    protected function createSesDriver(): SesDriver
    {
        return new SesDriver;
    }

    public function getDefaultDriver(): ?string
    {
        return null;
    }
}
