<?php

namespace Vormkracht10\Mails\Enums\Events;

enum Mailgun: string
{
    case CLICKED = 'clicked';
    case COMPLAINED = 'complained';
    case DELIVERED = 'delivered';
    case HARD_BOUNCED = 'failed';
    case OPENED = 'opened';
    case SOFT_BOUNCE = 'failed';
    case UNSUBSCRIBED = 'unsubscribed';
}
