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
            Mailgun::CLICK->value => Mapping::CLICK->value,
            Mailgun::COMPLAINT->value => Mapping::COMPLAINT->value,
            Mailgun::DELIVERY->value => Mapping::DELIVERY->value,
            Mailgun::HARD_BOUNCE->value => Mapping::BOUNCE->value,
            Mailgun::OPEN->value => Mapping::OPEN->value,
        ];
    }
}
