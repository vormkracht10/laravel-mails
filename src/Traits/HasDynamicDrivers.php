<?php

namespace Backstage\Mails\Traits;

trait HasDynamicDrivers
{
    protected array $drivers = [];

    public function via(): array
    {
        return $this->drivers;
    }

    /**
     * @param  string|string[]  $drivers
     */
    public function on($drivers, bool $merge = false): static
    {
        $drivers = array_wrap($drivers);

        if ($merge) {
            $drivers = array_merge($this->drivers, $drivers);
        }

        $via = [
            'discord' => \NotificationChannels\Discord\DiscordChannel::class,
            'mail' => \Illuminate\Notifications\Channels\MailChannel::class,
        ];

        $drivers = array_map(function ($driver) use ($via) {
            return $via[$driver];
        }, $drivers);

        $this->drivers = $drivers;

        return $this;
    }
}
