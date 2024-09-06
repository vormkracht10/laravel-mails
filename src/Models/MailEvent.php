<?php

namespace Vormkracht10\Mails\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Vormkracht10\Mails\Database\Factories\MailEventFactory;
use Vormkracht10\Mails\Enums\WebhookEventType;
use Vormkracht10\Mails\Events\MailEventLogged;

/**
 * @property Mail $mail
 * @property string $type
 * @property string $ip_address
 * @property string $hostname
 * @property string $platform
 * @property string $os
 * @property string $browser
 * @property string $user_agent
 * @property string $city
 * @property string $country_code
 * @property string $link
 * @property string $tag
 * @property object $payload
 * @property Carbon $occurred_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class MailEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'mail_id',
        'type',
        'ip_address',
        'hostname',
        'platform',
        'os',
        'browser',
        'user_agent',
        'city',
        'country_code',
        'link',
        'tag',
        'payload',
        'occurred_at',
    ];

    protected $casts = [
        'payload' => 'object',
        'created_at' => 'datetime',
        'occurred_at' => 'datetime',
        'type' => WebhookEventType::class,
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

    protected function getPastTenseNameAttribute(): string
    {
        return ucfirst(MappingPastTense::fromName($this->attributes['type'])->value);
    }

    protected function getEventClassAttribute(): string
    {
        return 'Vormkracht10\Mails\Events\Mail'.$this->past_tense_name;
    }
}
