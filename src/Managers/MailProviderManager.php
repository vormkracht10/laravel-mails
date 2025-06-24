<?php

namespace Backstage\Mails\Managers;

use Illuminate\Support\Manager;
use Backstage\Mails\Drivers\MailgunDriver;
use Backstage\Mails\Drivers\PostmarkDriver;

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

    public function getDefaultDriver(): ?string
    {
        return null;
    }
}
