<?php

namespace Vormkracht10\Mails\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Vormkracht10\Mails\Models\MailAttachment;

class MailAttachmentFactory extends Factory
{
    protected $model = MailAttachment::class;

    public function definition(): array
    {
        return [
            'type' => '...',
            'ip' => '',
            'hostname' => '',
            'payload' => '',
        ];
    }
}
