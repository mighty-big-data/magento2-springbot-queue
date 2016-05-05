<?php

namespace Springbot\Queue\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;

class Queue extends AbstractModel
{
    protected $queueFactory;

    /**
     * @param queueFactory $queueFactory
     * @param Context $context
     * @param Registry $registry
     */
    public function __construct(queueFactory $queueFactory, Context $context, Registry $registry)
    {
        $this->_init('Springbot\Queue\Model\ResourceModel\Queue');
        $this->queueFactory = $queueFactory;
        parent::__construct($context, $registry);
    }

    /**
     * @param $class
     * @param $method
     * @param array $args
     * @param string $queue
     * @param $priority
     * @param int $minutesInFuture
     */
    public function scheduleJob(
        $class,
        $method,
        array $args,
        $priority,
        $queue = 'default',
        $minutesInFuture = 0
    ) {
        $queueModel = $this->queueFactory->create();
        $queueModel->addData([
            'method' => $method,
            'args' => json_encode($args),
            'class' => $class,
            'command_hash' => sha1($method . json_encode($args)),
            'queue' => $queue,
            'priority' => $priority,
            'next_run_at' => date("Y-m-d H:i:s")
        ]);
        $queueModel->save();
    }

}
