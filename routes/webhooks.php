<?php

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;
use Backstage\Mails\Controllers\WebhookController;

Route::withoutMiddleware(VerifyCsrfToken::class)
    ->prefix(config('mails.webhooks.routes.prefix'))
    ->group(function () {
        Route::post('{provider}', WebhookController::class)->name('mails.webhook');
    });
