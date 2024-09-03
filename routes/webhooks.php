<?php

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;
use Vormkracht10\Mails\Controllers\MailgunWebhookController;
use Vormkracht10\Mails\Controllers\PostmarkWebhookController;

Route::withoutMiddleware(VerifyCsrfToken::class)
    ->prefix(config('mails.webhooks.routes.prefix'))
    ->group(function () {
        Route::post('mailgun/{type}', MailgunWebhookController::class)->name('mails.webhooks.mailgun');
        Route::post('postmark', PostmarkWebhookController::class)->name('mails.webhooks.postmark');
    });
