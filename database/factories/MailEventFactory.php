<?php

namespace Wnx\Sends\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Vormkracht10\Mails\Models\MailEvent;

class MailEventFactory extends Factory
{
    protected $model = MailEvent::class;

    public function definition()
    {
        return [
            'type' => '...',
            'ip' => '',
            'hostname' => '',
            'payload' => '',
        ];
    }
}
