<?php

namespace Vormkracht10\Mails\Enums\Events;

enum Mailtrap: string
{
    case CLICK = 'Click';
    case DELIVERY = 'Delivery';
    case HARD_BOUNCE = 'Hard bounce';
    case SOFT_BOUNCE = 'Soft bounce';
    case OPEN = 'Open';
}