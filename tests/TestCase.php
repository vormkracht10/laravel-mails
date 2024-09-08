<?php

namespace Vormkracht10\Mails\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use NotificationChannels\Discord\DiscordServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Vormkracht10\Mails\MailsServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Vormkracht10\\Mails\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            DiscordServiceProvider::class,
            MailsServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config([
            'database.default' => 'testing',
            'queue.default' => 'sync',
        ]);

        $migration = require __DIR__.'/../database/migrations/1_create_mails_table.php.stub';
        $migration->up();

        $migration = require __DIR__.'/../database/migrations/2_create_mail_attachments_table.php.stub';
        $migration->up();

        $migration = require __DIR__.'/../database/migrations/2_create_mail_events_table.php.stub';
        $migration->up();

        $migration = require __DIR__.'/../database/migrations/2_create_mailables_table.php.stub';
        $migration->up();
    }
}
