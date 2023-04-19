<?php

namespace Vormkracht10\LaravelMails\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Vormkracht10\LaravelMails\LaravelMails
 */
class LaravelMails extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Vormkracht10\LaravelMails\LaravelMails::class;
    }
}
