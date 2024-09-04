<?php

namespace Vormkracht10\Mails\Drivers;

use Vormkracht10\Mails\Enums\Events\Mailtrap;
use Vormkracht10\Mails\Enums\Events\Mapping;

class MailtrapDriver
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
            Mailtrap::CLICK->value => Mapping::CLICK->value,
            Mailtrap::DELIVERY->value => Mapping::DELIVERY->value,
            Mailtrap::HARD_BOUNCE->value => Mapping::BOUNCE->value,
            Mailtrap::OPEN->value => Mapping::OPEN->value,
            Mailtrap::SOFT_BOUNCE->value => Mapping::BOUNCE->value,
        ];
    }
}
