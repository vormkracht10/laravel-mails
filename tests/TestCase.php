<?php

namespace Vormkracht10\Mails\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
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
            MailsServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'sqlite');

        config()->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $migration = include __DIR__.'/../database/migrations/create_mailables_table.php.stub';
        $migration->up();

        $migration = include __DIR__.'/../database/migrations/create_mails_attachments_table.php.stub';
        $migration->up();

        $migration = include __DIR__.'/../database/migrations/create_mails_events_table.php.stub';
        $migration->up();

        $migration = include __DIR__.'/../database/migrations/create_mails_table.php.stub';
        $migration->up();
    }
}
