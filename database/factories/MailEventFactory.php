<?php

namespace Backstage\Mails\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Backstage\Mails\Models\MailEvent;

class MailEventFactory extends Factory
{
    protected $model = MailEvent::class;

    public function definition(): array
    {
        return [
            'type' => 'delivered',
            'payload' => [],
        ];
    }

    public function bounce(): Factory
    {
        return $this->state(function () {
            return [
                'type' => 'hard_bounced',
            ];
        });
    }
}
