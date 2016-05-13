<?php

namespace Springbot\Queue\Api;

use Magento\Checkout\Model\Cart;
use Magento\Checkout\Model\Session;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Model\AbstractModel;
use Springbot\Queue\Model\Queue;

/**
 * Class Config
 * @package Springbot\Main\Api
 */
class Jobs extends AbstractModel implements JobsInterface
{

    private $_queue;

    /**
     * @param Queue $queue
     * @param Context $context
     * @param Registry $registry
     */
    public function __construct(
        Queue $queue,
        Context $context,
        Registry $registry
    ) {
        $this->_queue = $queue;
        parent::__construct($context, $registry);
    }

    public function viewJobs()
    {
        return [
            'jobs' => $this->_queue->getCollection()->toArray()
        ];
    }

    public function process()
    {
        $result = $this->_queue->runNextJob();
        if ($result === true) {
            $message = "Job(s) run successfully";
        }
        else if ($result === false) {
            $message = "Job(s) failed";
        }
        else {
            $message = "No jobs left to run";
        }
        return [[
            'count' => $this->_queue->getCount(),
            'message' => $message
        ]];
    }

}
