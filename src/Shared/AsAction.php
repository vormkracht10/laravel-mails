<?php

namespace Vormkracht10\Mails\Shared;

trait AsAction
{
    public function __invoke()
    {
        return $this->handle(...func_get_args());
    }
}
