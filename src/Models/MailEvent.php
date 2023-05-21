<?php

namespace Vormkracht10\Mails\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Vormkracht10\Mails\Database\Factories\MailEventFactory;

class MailEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'mail_id',
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

    public function __construct()
    {
        $this->table = config('mails.table_names.events') ?: parent::getTable();
    }

    protected static function newFactory()
    {
        return new MailEventFactory();
    }

    public function mail(): void
    {
        $this->belongsTo(config('mails.models.mail'));
    }
}
