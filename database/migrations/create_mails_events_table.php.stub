<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create(config('mails.tables.mail_events'), function (Blueprint $table) {
            $table->id();
            $table->foreignId('mail_id');
            $table->string('type');
            $table->string('ip')->nullable();
            $table->string('hostname')->nullable();
            $table->json('payload')->nullable();
        });
    }
};
