<?php

namespace Vormkracht10\Mails\Enums\Events;

enum MappingPastTense: string
{
    case BOUNCE = 'bounced';
    case CLICK = 'clicked';
    case COMPLAINT = 'complained';
    case DELIVERY = 'delivered';
    case OPEN = 'opened';
    // case UNSUBSCRIBE = 'unsubscribed';

    public static function fromName(string $name): mixed
    {
        $name = strtoupper($name);

        return constant("self::$name");
    }
}
