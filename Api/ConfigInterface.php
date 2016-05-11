<?php

namespace Springbot\Queue\Api;

/**
 * Interface ConfigInterface
 *
 * @package Springbot\Queue\Api
 */
interface ConfigInterface
{
    /**
     * Get store configuration
     *
     * @return array
     */
    public function getConfig();
}
