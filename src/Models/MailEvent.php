<?php

namespace Vormkracht10\Mails\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'mail_id',
        'ip',
        'hostname',
        'payload',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'occurred_at' => 'datetime',
    ];

    protected static function newFactory()
    {
        return new MailEventFactory();
    }

    public function mail(): void
    {
        $this->belongsTo(config('mails.models.mail'));
    }
}
