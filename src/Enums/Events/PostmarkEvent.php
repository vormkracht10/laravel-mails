<?php

namespace Vormkracht10\Mails\Events;

enum PostmarkEvent: string
{
    case CLICKED = 'Click';
    case COMPLAINED = 'SpamComplaint';
    case DELIVERED = 'Delivery';
    case HARD_BOUNCED = 'Bounce';
    case OPENED = 'Open';
    case SOFT_BOUNCED = '';
    case UNSUBSCRIBED = '';
}
