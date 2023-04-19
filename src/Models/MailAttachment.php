<?php

namespace Vormkracht10\Mails\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Wnx\Sends\Database\Factories\MailFactory;

class MailAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename',
        'size',
        'body',
    ];

    protected $casts = [
        'filename' => 'string',
        'size' => 'integer',
        'body' => 'string',
    ];

    protected static function newFactory(): MailFactory
    {
        return new MailFactory();
    }

    public function mail(): BelongsTo
    {
        return $this->belongsTo(config('mails.models.mail'));
    }
}
