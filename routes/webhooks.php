<?php

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;
use Vormkracht10\Mails\Controllers\WebhookController;

Route::withoutMiddleware(VerifyCsrfToken::class)
    ->prefix(config('mails.webhooks.routes.prefix'))
    ->group(function () {
        Route::post('{driver}', WebhookController::class)->name('mails.webhook');
    });
