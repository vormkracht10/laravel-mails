<?php

use Illuminate\Support\Facades\Event;
use Backstage\Mails\Events\MailEventLogged;
use Backstage\Mails\Events\MailHardBounced;
use Backstage\Mails\Models\Mail;

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
