<?php

namespace Vormkracht10\Mails\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Vormkracht10\Mails\Database\Factories\MailEventFactory;
use Vormkracht10\Mails\Enums\Events\MappingPastTense;
use Vormkracht10\Mails\Events\MailEventLogged;

class MailEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'mail_id',
        'type',
        'ip',
        'hostname',
        'payload',
        'occurred_at',
    ];

    protected $casts = [
        'payload' => 'object',
        'created_at' => 'datetime',
        'occurred_at' => 'datetime',
    ];

    public function getTable()
    {
        return config('mails.database.tables.events');
    }

    protected static function booted(): void
    {
        static::creating(function (MailEvent $mailEvent) {
            event(MailEventLogged::class, $mailEvent);

            $eventClass = $mailEvent->eventClass;

            $eventClass::dispatch($mailEvent);
        });
    }

    protected static function newFactory(): Factory
    {
        return MailEventFactory::new();
    }

    public function mail()
    {
        return $this->belongsTo(config('mails.models.mail'));
    }

    protected function getPastTenceNameAttribute(): string
    {
        return ucfirst(MappingPastTense::fromName($this->attributes['type'])->value);
    }

    protected function getEventClassAttribute(): string
    {
        return 'Vormkracht10\Mails\Events\Mail'.$this->pastTenceName;
    }
}
