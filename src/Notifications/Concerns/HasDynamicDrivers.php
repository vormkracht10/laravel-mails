<?php

namespace Vormkracht10\Mails\Notifications\Concerns;

trait HasDynamicDrivers
{
    protected array $drivers = [];

    public function via(): array
    {
        return $this->drivers;
    }

    /**
     * @param string|string[] $drivers
     */
    public function on($drivers, bool $merge = false): static
    {
        $drivers = array_wrap($drivers);

        if ($merge) {
            $drivers = array_merge($this->drivers, $drivers);
        }

        $this->drivers = $drivers;

        return $this;
    }
}
