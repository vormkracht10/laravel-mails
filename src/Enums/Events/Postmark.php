<?php

namespace Vormkracht10\Mails\Enums\Events;

enum Postmark: string
{
    case CLICKED = 'Click';
    case COMPLAINED = 'SpamComplaint';
    case DELIVERED = 'Delivery';
    case HARD_BOUNCED = 'Bounce';
    case OPENED = 'Open';
    // case SOFT_BOUNCED = '';
    // case UNSUBSCRIBED = '';
}
