<?php

namespace Backstage\Mails\Facades;

use Illuminate\Support\Facades\Facade;
use Backstage\Mails\Contracts\MailDriverContract;
use Backstage\Mails\Contracts\MailProviderContract;

/**
 * @method static MailDriverContract with(string $driver)
 */
class MailProvider extends Facade
{
    protected static function getFacadeAccessor()
    {
        return MailProviderContract::class;
    }
}
