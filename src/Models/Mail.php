<?php

namespace Vormkracht10\Mails\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'sent_at' => 'immutable_datetime',
        'delivered_at' => 'immutable_datetime',
        'opens' => 'integer',
        'last_opened_at' => 'immutable_datetime',
        'clicks' => 'integer',
        'last_clicked_at' => 'immutable_datetime',
        'complained_at' => 'immutable_datetime',
        'bounced_at' => 'immutable_datetime',
        'permanent_bounced_at' => 'immutable_datetime',
        'rejected_at' => 'immutable_datetime',
    ];

    protected static function newFactory()
    {
        return new MailFactory();
    }

    public function events(): void
    {
        $this->hasMany(config('mails.models.event'));
    }
}
