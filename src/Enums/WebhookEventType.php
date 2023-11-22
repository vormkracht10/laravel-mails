<?php

namespace Vormkracht10\Mails\Enums;

enum WebhookEventType: string
{
    case BOUNCE = 'bounce';
    case CLICK = 'click';
    case COMPLAINT = 'complaint';
    case DELIVERY = 'delivery';
    case OPEN = 'open';
}
