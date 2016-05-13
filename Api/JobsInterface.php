<?php

namespace Springbot\Queue\Api;

use Magento\Framework\Controller\Result\Json;

/**
 * Interface JobsInterface
 *
 * @package Springbot\Queue\Api
 */
interface JobsInterface
{
    /**
     * Return a list of jobs
     *
     * @return array
     */
    public function viewJobs();

    /**
     * Process the next job(s) in the queue
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function process();
}
