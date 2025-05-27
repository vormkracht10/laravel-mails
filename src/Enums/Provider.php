<?php

namespace Vormkracht10\Mails\Enums;

enum Provider: string
{
    case POSTMARK = 'postmark';
    case MAILGUN = 'mailgun';
    case SES = 'ses';
}
