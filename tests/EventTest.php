<?php

use Illuminate\Support\Facades\Event;
use Vormkracht10\Mails\Events\MailBounced;
use Vormkracht10\Mails\Events\MailEventLogged;
use Vormkracht10\Mails\Models\Mail;

it('dispaches events when an mail is logged', function () {
    Event::fake([
        MailEventLogged::class,
        MailBounced::class,
    ]);

    Mail::factory()
        ->hasEvents(1, [
            'type' => 'bounce',
        ])
        ->create();

    Event::assertDispatched(MailEventLogged::class);
    Event::assertDispatched(MailBounced::class);
});
