<?php

namespace Vormkracht10\Mails\Enums\Events;

enum Mapping: string
{
    case ACCEPT = 'accept';
    case BOUNCE = 'bounce';
    case CLICK = 'click';
    case COMPLAINT = 'complaint';
    case DELIVERY = 'delivery';
    case OPEN = 'open';
    case UNSUBSCRIBE = 'unsubscribe';
}
