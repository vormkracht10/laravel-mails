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

    'webhooks' => [
        'routes' => [
            'prefix' => 'webhooks/mails',
        ],

        'queue' => env('MAILS_QUEUE_WEBHOOKS', false),
    ],

    // Logging mails
    'logging' => [

        // Enable logging of all sent mails to database

        'enabled' => env('MAILS_LOGGING_ENABLED', true),

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

        // Encrypt all attributes saved to database

        'encrypted' => env('MAILS_ENCRYPTED', true),

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
            'enabled' => env('MAILS_LOGGING_ATTACHMENTS_ENABLED', true),
            'disk' => env('FILESYSTEM_DISK', 'local'),
            'root' => 'mails/attachments',
        ],
    ],

    // Notifications for important mail events

    'notifications' => [

        // Possible notification channels: discord, mail, slack, telegram

        'channels' => [
            'mail' => ['m@rkvaneijk.nl'],
            // 'discord' => ['1234567890'],
            // 'slack' => ['https://hooks.slack.com/services/...'],
            // 'telegram' => ['1234567890'],
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
            'bounce' => [
                'notify' => [
                    'mail',
                    //     'discord',
                    //     'slack',
                    //     'telegram',
                ],
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
            'deliveryrate' => [
                'treshold' => 99, // in %
                // 'notify' => [
                //     'mail',
                //     'discord',
                //     'slack',
                //     'telegram',
                // ],
            ],
            'complaint' => [
                // 'notify' => [
                //     'mail',
                //     'discord',
                //     'slack',
                //     'telegram',
                // ],
            ],
            'unsent' => [
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
