<?php

namespace Backstage\Mails\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Backstage\Mails\Models\MailAttachment;

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
