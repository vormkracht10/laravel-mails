<?php

namespace Vormkracht10\Mails\Enums;

enum EventType: string
{
    case ACCEPTED = 'accepted';
    case CLICKED = 'clicked';
    case COMPLAINED = 'complained';
    case DELIVERED = 'delivered';
    case SOFT_BOUNCED = 'soft_bounced';
    case HARD_BOUNCED = 'hard_bounced';
    case OPENED = 'opened';
    case UNSUBSCRIBED = 'unsubscribed';
}
