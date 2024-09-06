<?php

namespace Vormkracht10\Mails\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Vormkracht10\Mails\Database\Factories\MailAttachmentFactory;

/**
 * @property-read string $disk
 * @property-read string $uuid
 * @property-read string $filename
 * @property-read string $mime
 * @property-read bool $inline
 * @property-read int $size
 * @property-read Mail $mail
 */
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

    public function getStoragePathAttribute(): string
    {
        return rtrim(config('mails.logging.attachments.root'), '/').'/'.$this->getKey().'/'.$this->filename;
    }

    public function getFileDataAttribute(): string
    {
        return Storage::disk($this->disk)->get($this->storagePath);
    }
}
