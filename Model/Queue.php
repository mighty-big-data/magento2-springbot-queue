<?php

namespace Springbot\Queue\Model;

use Exception;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Data\Collection;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Springbot\Queue\Model\ResourceModel\Job\Collection as JobCollection;

class Queue extends AbstractModel
{
    protected $jobFactory;
    protected $jobCollection;
    protected $scopeConfig;

    /**
     * @param JobFactory $jobFactory
     * @param JobCollection $jobCollection
     * @param Context $context
     * @param Registry $registry
     * @param ScopeConfigInterface $scopeConfigInterface
     */
    public function __construct(
        JobFactory $jobFactory,
        JobCollection $jobCollection,
        Context $context,
        Registry $registry,
        ScopeConfigInterface $scopeConfigInterface
    ) {
        $this->_init('Springbot\Queue\Model\ResourceModel\Job');
        $this->jobFactory = $jobFactory;
        $this->jobCollection = $jobCollection;
        $this->scopeConfig = $scopeConfigInterface;
        parent::__construct($context, $registry);
    }

    /**
     * @param $class
     * @param $method
     * @param array $args
     * @param string $queue
     * @param $priority
     * @param $time
     * @return boolean
     */
    public function scheduleJob(
        $class,
        $method,
        array $args,
        $priority = 1,
        $queue = 'default',
        $time = null
    ) {
        try {
            $hash = sha1($method . json_encode($args));
            if ($this->jobExists($hash)) {
                return false;
            }
            else {
                if ($time) {
                    $nextRunAt = date("Y-m-d H:i:s", strtotime($time));
                }
                else {
                    $nextRunAt = date("Y-m-d H:i:s");
                }
                $jobModel = $this->jobFactory->create();
                $jobModel->addData([
                    'method'      => $method,
                    'args'        => json_encode($args),
                    'class'       => $class,
                    'hash'        => $hash,
                    'queue'       => $queue,
                    'priority'    => $priority,
                    'next_run_at' => $nextRunAt
                ]);
                $jobModel->save();
                return true;
            }
        } catch (\Exception $e) {
            $this->_logger->debug("Failed to enqueue job: " . $e->getMessage());
            return false;
        }
    }

    public function jobExists($hash)
    {
        $collection = $this->jobCollection->addFieldToFilter('hash', $hash);
        $item = $collection->getFirstItem();
        return (!$item->isEmpty());
    }

    public function getNextJob()
    {
        $nextJob = $this->getRunnableJobs()
            ->setOrder('priority', Collection::SORT_ORDER_ASC)
            ->addOrder('id', Collection::SORT_ORDER_ASC)
            ->setPageSize(1)
            ->setCurPage(1)
            ->getFirstItem();
        if (!$nextJob->isEmpty()) {
            return $nextJob;
        }
        else {
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
            } catch (Exception $e) {
                $attempts = $nextJob->getData('attempts');
                $attempts = (!$attempts) ? 0 : $attempts;
                $attempts++;
                $nextJob->setData('error', $e->getMessage());
                $nextJob->setData('attempts', $attempts);
                $nextJob->setNextRunAt();
                $nextJob->save();
                return false;
            }
        }
        else {
            return null;
        }
    }

    /**
     * Process the next N jobs in the queue where N is the max_jobs value
     *
     * @return bool|null
     */
    public function process()
    {
        $maxJobs = $this->scopeConfig->getValue('springbot/queue/max_jobs');
        if (!is_numeric($maxJobs)) {
            $maxJobs = 1;
        }

        for ($i = 1; $i <= $maxJobs; $i++) {
            if ($this->runNextJob() === null) {
                return null;
            }
        }
        return true;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->getRunnableJobs()
            ->count();
    }

    /**
     * @return JobCollection
     */
    private function getRunnableJobs()
    {
        $this->jobCollection->clear();
        return $this->jobCollection
            ->addFieldToFilter(
                ['next_run_at', 'next_run_at'],
                [['lteq' => date("Y-m-d H:i:s")], ['null' => 'null']]
            )
            ->addFieldToFilter(
                ['attempts', 'attempts'],
                [['lt' => 10], ['null' => 'null']]
            )
            ->setPageSize(20);
    }

}
