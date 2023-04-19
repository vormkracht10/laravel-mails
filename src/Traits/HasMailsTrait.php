<?php

namespace Vormkracht10\Mails;

use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasMailsTrait
{
    public function mails(): MorphToMany
    {
        return $this->morphToMany(config('mails.model'), 'mailable', 'mailables', 'mailable_id', 'mail_id');
    }
}
