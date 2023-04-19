<?php

use Vormkracht10\Mails\Models\Mail;
use Vormkracht10\Mails\Models\MailAttachment;
use Vormkracht10\Mails\Models\MailEvent;

return [

    // Eloquent model to use for sent emails

    'models' => [
        'mail' => Mail::class,
        'event' => MailEvent::class,
        'attachment' => MailAttachment::class,
    ],

    // Table names for saving sent emails and polymorphic relations to database

    'tables' => [
        'mails' => 'mails',
        'events' => 'mail_events',
        'polymorph' => 'mailables',
    ],

    'headers' => [
        'uuid' => 'X-Laravel-Mails-UUID',
    ],

    // Encrypt all attributes saved to database

    'encrypt' => false,

    // Logging mails
    'logging' => [

        // Enable logging of all sent mails to database

        'enable' => true,

        // Specify attributes to log in database

        'attributes' => [
            'subject',
            'body_html',
            'body_text',
            'from',
            'to',
            'reply_to',
            'cc',
            'bcc',
        ],

        // Track following events using webhooks from email provider

        'tracking' => [
            'bounces' => true,
            'clicks' => true,
            'complaints' => true,
            'deliveries' => true,
            'opens' => true,
        ],

        // Enable saving mail attachments to disk

        'attachments' => [
            'enable' => false,
            'disk' => env('FILESYSTEM_DISK', 'local'),
            'path' => 'mails/attachments',
        ],
    ],

    // Notifications for important mail events

    'notifications' => [

        // Possible notification channels: discord, mail, slack, telegram

        'channels' => [
            // 'mail' => 'mail:support@vormkracht10.nl',
            // 'discord' => 'discord:1234567890',
            // 'slack' => 'slack:https://hooks.slack.com/services/...',
            // 'telegram' => 'telegram:1234567890',
        ],

        // Get notified when a bounce occurred

        'events' => [
            'default' => [
                'notify' => [
                    // 'mail',
                    // 'discord',
                    // 'slack',
                    // 'telegram',
                ],
            ],
            'bounces' => [
                // 'notify' => [
                //     'mail',
                //     'discord',
                //     'slack',
                //     'telegram',
                // ],
            ],
            'bouncerate' => [
                'treshold' => 1, // in %
                // 'notify' => [
                //     'mail',
                //     'discord',
                //     'slack',
                //     'telegram',
                // ],
            ],
            // Get notified when a spam complaint occurred
            'complaints' => [
                // 'notify' => [
                //     'mail',
                //     'discord',
                //     'slack',
                //     'telegram',
                // ],
            ],
        ],
    ],

];
