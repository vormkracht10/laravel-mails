<?php

namespace Vormkracht10\Mails;

use App\Shared\Terminal;

if (! function_exists('console')) {
    function console(): Terminal
    {
        return new Terminal;
    }
}
