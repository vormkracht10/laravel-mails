<?php

namespace Vormkracht10\Mails\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Vormkracht10\Mails\Database\Factories\MailFactory;

/**
 * @property-read string $uuid
 * @property-read string $mail_class
 * @property-read string $subject
 * @property-read string $html
 * @property-read ?string $content
 * @property-read array $from
 * @property-read array $reply_to
 * @property-read array $to
 * @property-read array $cc
 * @property-read array $bcc
 * @property int $opens
 * @property int $clicks
 * @property ?Carbon $sent_at
 * @property ?Carbon $delivered_at
 * @property ?Carbon $last_opened_at
 * @property ?Carbon $last_clicked_at
 * @property ?Carbon $complained_at
 * @property ?Carbon $soft_bounced_at
 * @property ?Carbon $resent_at
 * @property ?Carbon $hard_bounced_at
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 *
 * @method static Builder forUuid(string $uuid)
 * @method static Builder forMailClass(string $mailClass)
 * @method static Builder<static> sent()
 * @method static Builder<static> delivered()
 * @method static Builder<static> opened()
 * @method static Builder<static> clicked()
 * @method static Builder<static> softBounced()
 * @method static Builder<static> hardBounced()
 * @method static Builder<static> unsent()
 */
class Mail extends Model
{
    use HasFactory, MassPrunable;

    protected $fillable = [
        'uuid',
        'mail_class',
        'subject',
        'html',
        'text',
        'from',
        'reply_to',
        'to',
        'cc',
        'bcc',
        'opens',
        'clicks',
        'sent_at',
        'resent_at',
        'delivered_at',
        'last_opened_at',
        'last_clicked_at',
        'complained_at',
        'soft_bounced_at',
        'hard_bounced_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'uuid' => 'string',
        'subject' => 'string',
        'from' => 'json',
        'reply_to' => 'json',
        'to' => 'json',
        'cc' => 'json',
        'bcc' => 'json',
        'opens' => 'integer',
        'clicks' => 'integer',
        'sent_at' => 'datetime',
        'resent_at' => 'datetime',
        'accepted_at' => 'datetime',
        'delivered_at' => 'datetime',
        'last_opened_at' => 'datetime',
        'last_clicked_at' => 'datetime',
        'complained_at' => 'datetime',
        'soft_bounced_at' => 'datetime',
        'hard_bounced_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getTable()
    {
        return config('mails.database.tables.mails');
    }

    protected static function newFactory(): Factory
    {
        return MailFactory::new();
    }

    public function prunable(): Builder
    {
        $pruneAfter = config('mails.database.pruning.after', 30);

        return static::where('created_at', '<=', now()->subDays($pruneAfter));
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(config('mails.models.attachment'));
    }

    public function events(): HasMany
    {
        return $this->hasMany(config('mails.models.event'))->orderBy('occurred_at', 'desc');
    }

    public function scopeResent(Builder $query): Builder
    {
        return $query->whereNotNull('resent_at');
    }

    public function scopeDelivered(Builder $query): Builder
    {
        return $query->whereNotNull('delivered_at');
    }

    public function scopeClicked(Builder $query): Builder
    {
        return $query->whereNotNull('last_clicked_at');
    }

    public function scopeOpened(Builder $query): Builder
    {
        return $query->whereNotNull('last_opened_at');
    }

    public function scopeComplained(Builder $query): Builder
    {
        return $query->whereNotNull('complained_at');
    }

    public function scopeSoftBounced(Builder $query): Builder
    {
        return $query->whereNotNull('soft_bounced_at');
    }

    public function scopeHardBounced(Builder $query): Builder
    {
        return $query->whereNotNull('hard_bounced_at');
    }

    public function scopeSent(Builder $query): Builder
    {
        return $query->whereNotNull('sent_at');
    }

    public function scopeUnsent(Builder $query): Builder
    {
        return $query->whereNull('sent_at');
    }

    public function getStatusAttribute(): string
    {
        if ($this->hard_bounced_at) {
            return __('Hard Bounced');
        } elseif ($this->soft_bounced_at) {
            return __('Soft Bounced');
        } elseif ($this->complained_at) {
            return __('Complained');
        } elseif ($this->last_clicked_at) {
            return __('Clicked');
        } elseif ($this->last_opened_at) {
            return __('Opened');
        } elseif ($this->delivered_at) {
            return __('Delivered');
        } elseif ($this->resent_at) {
            return __('Resent');
        } elseif ($this->sent_at) {
            return __('Sent');
        } else {
            return __('Unsent');
        }
    }
}
