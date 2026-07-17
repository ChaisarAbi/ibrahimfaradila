<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Commands extends BaseConfig
{
    /**
     * --------------------------------------------------------------------------
     * Auto-discover Commands
     * --------------------------------------------------------------------------
     *
     * Disables auto-discovery for certain command groups.
     */
    public array $disables = [];

    /**
     * --------------------------------------------------------------------------
     * Available Commands
     * --------------------------------------------------------------------------
     *
     * List all available commands to be registered.
     */
    public array $commands = [
        \App\Commands\NotificationsSend::class,
    ];
}