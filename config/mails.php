<?php

use Vormkracht10\Mails\Models\Mail;
use Vormkracht10\Mails\Models\MailEvent;

return [

    // Eloquent model to use for sent emails

    'models' => [
        'mail' => Mail::class,
        'event' => MailEvent::class,
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
    ],

    // Notifications for important mail events

    'notifications' => [

        // Possible notification channels: discord, mail, slack, telegram

        // Get notified when a bounce occurred

        'bounces' => [
            // Email addresses
            'mail' => [
                // 'info@example.com',
            ],
            // Discord channel ID('s)
            'discord' => [
                // 1234567890,
            ],
            // Slack Webhook URL(s)
            'slack' => [
                // 'https://hooks.slack.com/services/...',
            ],
            // Telegram channel ID('s)
            'telegram' => [
                // 1234567890,
            ],
        ],

        // Get notified when bouncerate is too high

        'bouncerate' => [
            'treshold' => 1, // in %
            // Email addresses
            'mail' => [
                // 'info@example.com',
            ],
            // Discord channel ID('s)
            'discord' => [
                // 1234567890,
            ],
            // Slack Webhook URL(s)
            'slack' => [
                // 'https://hooks.slack.com/services/...',
            ],
            // Telegram channel ID('s)
            'telegram' => [
                // 1234567890,
            ],
        ],

        // Get notified when a spam complaint occurred

        'complaints' => [
            // Email addresses
            'mail' => [
                // 'info@example.com',
            ],
            // Discord channel ID('s)
            'discord' => [
                // 1234567890,
            ],
            // Slack Webhook URL(s)
            'slack' => [
                // 'https://hooks.slack.com/services/...',
            ],
            // Telegram channel ID('s)
            'telegram' => [
                // 1234567890,
            ],
        ],
    ],

];
