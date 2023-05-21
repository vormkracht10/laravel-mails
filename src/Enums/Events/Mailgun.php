<?php

namespace Vormkracht10\Mails\Enums\Events;

enum Mailgun: string
{
    case CLICK = 'clicked';
    case COMPLAINT = 'complained';
    case DELIVERY = 'delivered';
    case HARD_BOUNCE = 'permanent_fail';
    case OPEN = 'opened';
    case SOFT_BOUNCE = 'temporary_fail';
    // case UNSUBSCRIBE = 'unsubscribed';
}
