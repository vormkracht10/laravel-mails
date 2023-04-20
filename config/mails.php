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

    'table_names' => [
        'mails' => 'mails',
        'attachments' => 'mails_attachments',
        'events' => 'mails_events',
        'polymorph' => 'mailables',
    ],

    'headers' => [
        'uuid' => 'X-Laravel-Mails-UUID',
    ],

    // Encrypt all attributes saved to database

    'encryption' => false,

    // Logging mails
    'logging' => [

        // Enable logging of all sent mails to database

        'enabled' => true,

        // Specify attributes to log in database

        'attributes' => [
            'subject',
            'from',
            'to',
            'reply_to',
            'cc',
            'bcc',
            'html',
            'text',
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
            'enabled' => false,
            'disk' => env('FILESYSTEM_DISK', 'local'),
            'root' => 'mails/attachments',
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
