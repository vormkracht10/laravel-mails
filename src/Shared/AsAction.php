<?php

namespace Vormkracht10\Mails\Shared;

trait AsAction
{
    public function __invoke(...$parameters)
    {
        return $this->handle(...$parameters);
    }
}
