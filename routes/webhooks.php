<?php

use Illuminate\Support\Facades\Route;
use Vormkracht10\Mails\Controllers\MailgunWebhookController;
use Vormkracht10\Mails\Controllers\PostmarkWebhookController;

Route::group(['middleware' => 'signed', 'prefix' => config('mails.webhooks.routes.prefix')], function () {
    Route::post('mailgun/{type}', MailgunWebhookController::class)->name('mails.webhooks.mailgun');
    Route::post('postmark', PostmarkWebhookController::class)->name('mails.webhooks.postmark');
});
