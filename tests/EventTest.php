<?php

use Illuminate\Support\Facades\Event;
use Vormkracht10\Mails\Events\MailEventLogged;
use Vormkracht10\Mails\Events\MailHardBounced;
use Vormkracht10\Mails\Models\Mail;

it('dispaches events when an mail is logged', function () {
    Event::fake([
        MailEventLogged::class,
        MailHardBounced::class,
    ]);

    Mail::factory()
        ->hasEvents(1, [
            'type' => 'hard_bounced',
        ])
        ->create();

    Event::assertDispatched(MailEventLogged::class);
    Event::assertDispatched(MailHardBounced::class);
});
