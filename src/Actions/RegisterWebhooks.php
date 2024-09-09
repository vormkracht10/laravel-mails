<?php

namespace Vormkracht10\Mails\Actions;

use Illuminate\Console\Concerns\InteractsWithIO;
use Illuminate\Console\View\Components\Factory;
use Vormkracht10\Mails\Facades\MailProvider;
use Vormkracht10\Mails\Shared\AsAction;

class RegisterWebhooks
{
    use AsAction, InteractsWithIO;

    public function handle(string $provider, Factory $components)
    {
        MailProvider::with($provider)->registerWebhooks(
            components: $components
        );
    }
}
