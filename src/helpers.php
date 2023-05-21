<?php

namespace Vormkracht10\Mails;

use Vormkracht10\Mails\Shared\Terminal;

if (! function_exists('console')) {
    function console(): Terminal
    {
        return new Terminal;
    }
}
