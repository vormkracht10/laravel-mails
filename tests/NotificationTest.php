<?php

use Illuminate\Support\Facades\Notification;
use Vormkracht10\Mails\Models\Mail;
use Vormkracht10\Mails\Notifications\BounceNotification;

it('will send notification on bounce', function () {
    Notification::fake();

    Mail::factory()
        ->hasEvents(1, [
            'type' => 'bounce',
        ])
        ->create();

    Notification::assertSentTimes(
        BounceNotification::class, 1
    );
});
