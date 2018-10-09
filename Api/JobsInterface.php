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
     * @return string[]
     */
    public function viewJobs();

    /**
     * Process the next job(s) in the queue
     *
     * @return \Springbot\Queue\Api\ProcessResponseInterface
     */
    public function process();
    
    /**
     * Process the next job(s) in the queue
     * 
     * @param string $jobId Users name.
     *
     * @return \Springbot\Queue\Api\ProcessResponseInterface
     */
    public function deleteJob($jobId);

    /**
     * Process the next job(s) in the queue
     *
     * @return \Springbot\Queue\Api\ProcessResponseInterface
     */
    public function clearJobs();
}
