<?php

namespace Vormkracht10\Mails\Enums\Events;

enum Postmark: string
{
    case CLICK = 'Click';
    case COMPLAINT = 'SpamComplaint';
    case DELIVERY = 'Delivery';
    case HARD_BOUNCE = 'Bounce';
    case OPEN = 'Open';
    // case SOFT_BOUNCE = '';
    // case UNSUBSCRIBE = '';
}
