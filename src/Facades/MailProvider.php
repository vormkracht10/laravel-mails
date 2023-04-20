<?php

namespace Vormkracht10\Mails\Facades;

use Illuminate\Support\Facades\Facade;
use Vormkracht10\Mails\Contracts\MailProviderContract;

class MailProvider extends Facade
{
    protected static function getFacadeAccessor()
    {
        return MailProviderContract::class;
    }
}
