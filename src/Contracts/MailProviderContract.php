<?php

namespace Vormkracht10\Mails\Contracts;

interface MailProviderContract
{
    public function driver($driver = null);
}
