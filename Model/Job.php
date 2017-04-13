<?php

namespace Springbot\Queue\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\ObjectManagerInterface;
use Exception;

class Job extends AbstractModel
{

    private $_objectManager;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ObjectManagerInterface $objectManager
    ) {
        $this->_init('Springbot\Queue\Model\ResourceModel\Job');
        $this->_objectManager = $objectManager;
        parent::__construct($context, $registry);
    }

    public function run()
    {
        $class = $this->getData('class');
        $method = $this->getData('method');
        $args = json_decode($this->getData('args'), true);
        if (class_exists($class)) {
            $object = $this->_objectManager->get($class);
            if (method_exists($object, $method)) {
                return call_user_func_array([$object, $method], $args);
            } else {
                throw new Exception("Method {$method} does not exist in class {$class}");
            }
        } else {
            throw new Exception("Class {$class} does not exist");
        }
    }

    public function setNextRunAt()
    {
        $this->setData('run_at', $this->_calculateNextRunAt());
    }

    protected function _calculateNextRunAt()
    {
        $attempts = $this->getAttempts();
        $expMinutes = pow(2, $attempts);
        $nextRun = date("Y-m-d H:i:s", strtotime("+$expMinutes minutes"));
        return $nextRun;
    }
}
