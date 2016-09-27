<?php

namespace Springbot\Queue\Api;

use Magento\Framework\Controller\Result\Json;

/**
 * Interface ProcessResponseInterface
 * @package Springbot\Queue\Api
 */
interface ProcessResponseInterface
{

    /**
     * @return string
     */
    public function getMessage();

    /**
     * @return int
     */
    public function getCount();
}
