<?php

namespace Vormkracht10\Mails\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Vormkracht10\Mails\Models\MailEvent;

class MailEventFactory extends Factory
{
    protected $model = MailEvent::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => 'delivery',
            'payload' => [],
        ];
    }

    public function bounce(): Factory
    {
        return $this->state(function () {
            return [
                'type' => 'bounce',
            ];
        });
    }
}
