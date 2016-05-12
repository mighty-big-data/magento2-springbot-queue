<?php

namespace Springbot\Queue\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Springbot\Queue\Model\ResourceModel\Job\Collection as JobCollection;
use Exception;

class Queue extends AbstractModel
{
    protected $jobFactory;
    protected $jobCollection;

    /**
     * @param JobFactory $jobFactory
     * @param JobCollection $jobCollection
     * @param Context $context
     * @param Registry $registry
     */
    public function __construct(
        JobFactory $jobFactory,
        JobCollection $jobCollection,
        Context $context,
        Registry $registry
    ) {
        $this->_init('Springbot\Queue\Model\ResourceModel\Job');
        $this->jobFactory = $jobFactory;
        $this->jobCollection = $jobCollection;
        parent::__construct($context, $registry);
    }

    /**
     * @param $class
     * @param $method
     * @param array $args
     * @param string $queue
     * @param $priority
     * @param $time
     */
    public function scheduleJob(
        $class,
        $method,
        array $args,
        $priority = 1,
        $queue = 'default',
        $time = null
    ) {
        if ($time) {
            $nextRunAt = date("Y-m-d H:i:s", strtotime($time));
        } else {
            $nextRunAt = date("Y-m-d H:i:s");
        }
        $jobModel = $this->jobFactory->create();
        $jobModel->addData([
            'method' => $method,
            'args' => json_encode($args),
            'class' => $class,
            'command_hash' => sha1($method . json_encode($args)),
            'queue' => $queue,
            'priority' => $priority,
            'next_run_at' => $nextRunAt
        ]);
        $jobModel->save();
    }

    public function getNextJob()
    {
        $nextJob = $this->jobCollection
            ->setPageSize(2)
            ->setCurPage(1)
            ->getFirstItem();
        if ($nextJob) {
            return $nextJob;
        } else {
            return null;
        }
    }

    /**
     * @return bool|null
     */
    public function runNextJob()
    {
        if ($nextJob = $this->getNextJob()) {
            try {
                $nextJob->run();
                $nextJob->delete();
                return true;
            }
            catch (Exception $e) {
                $attempts = $nextJob->getData('attempts');
                $attempts = (!$attempts) ? 0 : $attempts;
                $attempts++;
                $nextJob->setData('error', $e->getMessage());
                $nextJob->setData('attempts', $attempts);
                $nextJob->save();
                return false;
            }
        }
        else {
            return null;
        }
    }

}
