<?php

namespace Vormkracht10\Mails\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Mailgun\Mailgun;
use Vormkracht10\Mails\Database\Factories\MailEventFactory;
use Vormkracht10\Mails\Drivers\PostmarkDriver;
use Vormkracht10\Mails\Enums\EventType;
use Vormkracht10\Mails\Events\MailEventLogged;

/**
 * @property Mail $mail
 * @property EventType $type
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
        'unsuppressed_at'
    ];

    protected $casts = [
        'type' => EventType::class,
        'payload' => 'object',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'occurred_at' => 'datetime',
        'unsuppressed_at' => 'datetime',
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

            if (class_exists($eventClass)) {
                $eventClass::dispatch($mailEvent);
            }
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

    protected function getEventClassAttribute(): string
    {
        return 'Vormkracht10\Mails\Events\Mail' . Str::studly($this->type->value);
    }

    public function unSuppress()
    {
        if (config('mail.default') === 'postmark') {
            $client = Http::asJson()
                ->withHeaders([
                    'X-Postmark-Server-Token' => config('services.postmark.token')
                ])
                ->baseUrl('https://api.postmarkapp.com/');

            $streamId = 'broadcast';
            $response = $client->post("message-streams/{$streamId}/suppressions/delete", [
                'Suppressions' => [
                    [
                        'emailAddress' => key($this->mail->to)
                    ]
                ]
            ]);

            if ($response->successful()) {
                $this->update(['unsuppressed_at' => now()]);
            } else {
                throw new \Exception('Failed to unsuppress email address due to ' . $response);
            }
        }

        if (config('mail.default') === 'mailgun') {
            $mailgun = Mailgun::create(config('services.mailgun.secret'), 'https://api.mailgun.net/v3');

            $response = $mailgun->suppressions()->unsubscribes()->delete(config('services.mailgun.domain'), key($this->mail->to));

            if ($response ) {
                $this->update(['unsuppressed_at' => now()]);
            } else {
                throw new \Exception('Failed to unsuppress email address due to ' . $response);
            }
        }
    }
}
