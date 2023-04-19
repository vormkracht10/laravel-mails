<?php

namespace Vormkracht10\Mails\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Vormkracht10\Mails\LaravelMails
 */
class LaravelMails extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Vormkracht10\Mails\LaravelMails::class;
    }
}
