<?php

namespace Vormkracht10\Mails\Drivers;

use Vormkracht10\Mails\Enums\Events\Mailgun;

class MailgunDriver
{
    protected $mailModel;

    protected $mailEventModel;

    public function __construct()
    {
        $this->mailModel = config('mails.models.mail');
        $this->mailEventModel = config('mails.models.event');
    }

    public function events()
    {
        return [
            Mailgun::CLICKED->value => 'clicked',
            Mailgun::COMPLAINED->value => 'complained',
            Mailgun::DELIVERED->value => 'delivered',
            Mailgun::HARD_BOUNCED->value => 'bounced',
            Mailgun::OPENED->value => 'opened',
        ];
    }
}
