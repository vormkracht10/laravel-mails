<?php

namespace Vormkracht10\Mails\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Wnx\Sends\Database\Factories\MailFactory;

/**
 * @property-read string $uuid
 * @property-read string $mail_class
 * @property-read string $subject
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
 * @property ?Carbon $hard_bounced_at
 *
 * @method static Builder forUuid(string $uuid)
 * @method static Builder forMailClass(string $mailClass)
 */
class Mail extends Model
{
    use HasFactory;

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
        'delivered_at' => 'datetime',
        'last_opened_at' => 'datetime',
        'last_clicked_at' => 'datetime',
        'complained_at' => 'datetime',
        'soft_bounced_at' => 'datetime',
        'hard_bounced_at' => 'datetime',
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = config('mails.table_names.mails') ?: parent::getTable();
    }

    protected static function newFactory(): MailFactory
    {
        return new MailFactory();
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(config('mails.models.attachment'));
    }

    public function events(): HasMany
    {
        return $this->hasMany(config('mails.models.event'));
    }
}
