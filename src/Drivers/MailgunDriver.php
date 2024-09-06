<?php

namespace Vormkracht10\Mails\Drivers;

use Vormkracht10\Mails\Enums\Events\Mailgun;
use Vormkracht10\Mails\Enums\Events\Mapping;

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
            Mailgun::ACCEPTED->value => Mapping::ACCEPT->value,
            Mailgun::CLICKED->value => Mapping::CLICK->value,
            Mailgun::COMPLAINED->value => Mapping::COMPLAINT->value,
            Mailgun::DELIVERED->value => Mapping::DELIVERY->value,
            Mailgun::FAILED->value => Mapping::BOUNCE->value,
            Mailgun::OPENED->value => Mapping::OPEN->value,
        ];
    }
}
