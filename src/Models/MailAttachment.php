<?php

namespace Vormkracht10\Mails\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Vormkracht10\Mails\Database\Factories\MailAttachmentFactory;

class MailAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'disk',
        'uuid',
        'filename',
        'mime',
        'inline',
        'size',
    ];

    protected $casts = [
        'disk' => 'string',
        'uuid' => 'string',
        'filename' => 'string',
        'mime' => 'string',
        'inline' => 'boolean',
        'size' => 'integer',
    ];

    public function getTable()
    {
        return config('mails.database.tables.attachments');
    }

    protected static function newFactory(): Factory
    {
        return MailAttachmentFactory::new();
    }

    public function mail(): BelongsTo
    {
        return $this->belongsTo(config('mails.models.mail'));
    }
}
