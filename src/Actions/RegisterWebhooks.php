<?php

namespace Vormkracht10\Mails\Actions;

use Vormkracht10\Mails\Shared\AsAction;
use Vormkracht10\Mails\Facades\MailProvider;
use Illuminate\Console\View\Components\Factory;
use Illuminate\Console\Concerns\InteractsWithIO;

class RegisterWebhooks
{
    use AsAction, InteractsWithIO;

    public function handle(Factory $components)
    {
        MailProvider::with('postmark')->registerWebhooks(
            components: $components
        );
    }
}