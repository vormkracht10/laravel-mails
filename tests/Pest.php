<?php

use Illuminate\Support\Facades\Mail;
use Backstage\Mails\Tests\TestCase;

uses(TestCase::class)
    ->in(__DIR__);

beforeEach(function () {
    Mail::fake();
});
