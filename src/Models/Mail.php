<?php

namespace Vormkracht10\Mails\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Wnx\Sends\Database\Factories\MailFactory;

class Mail extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'mail_class',
        'subject',
        'content',
        'from',
        'reply_to',
        'to',
        'cc',
        'bcc',
        'sent_at',
        'delivered_at',
        'opens',
        'last_opened_at',
        'clicks',
        'last_clicked_at',
        'complained_at',
        'bounced_at',
        'permanent_bounced_at',
        'rejected_at',
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
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'opens' => 'integer',
        'last_opened_at' => 'datetime',
        'clicks' => 'integer',
        'last_clicked_at' => 'datetime',
        'complained_at' => 'datetime',
        'bounced_at' => 'datetime',
        'permanent_bounced_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    protected static function newFactory(): MailFactory
    {
        return new MailFactory();
    }

    public function events(): HasMany
    {
        return $this->HasMany(config('mails.models.event'));
    }
}
