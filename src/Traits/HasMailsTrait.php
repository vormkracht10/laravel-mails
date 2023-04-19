<?php

namespace Vormkracht10\Mails\Traits;

use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasMailsTrait
{
    public function mails(): MorphToMany
    {
        return $this->morphToMany(config('mails.model'), 'mailable', 'mailables', 'mailable_id', 'mail_id');
    }

    public function events(): HasManyThrough
    {
        return $this->hasManyThrough(config('mails.models.event'), config('mails.models.mail'));
    }
}
