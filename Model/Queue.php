<?php

namespace Springbot\Queue\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\ObjectManagerInterface as ObjectManager;


class Queue extends AbstractModel
{
    protected $queueFactory;

    /**
     * @param queueFactory $queueFactory
     */
    public function __construct(
        queueFactory $queueFactory,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry
    )
    {
        $this->_init('Springbot\Queue\Model\ResourceModel\Queue');
        $this->queueFactory = $queueFactory;
        parent::__construct($context, $registry);
    }

    /**
     * @param $method
     * @param array $args
     * @param $priority
     * @param string $queue
     * @param int $minutesInFuture
     */
    public function scheduleJob(
        $method,
        array $args,
        $class,
        $queue = 'default',
        $priority,
        $minutesInFuture = 0
    ) {
        $queueModel = $this->queueFactory->create('Springbot\Queue\Model\Queue');
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
