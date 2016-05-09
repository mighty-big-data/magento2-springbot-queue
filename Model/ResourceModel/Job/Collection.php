<?php

namespace Springbot\Queue\Model\ResourceModel\Job;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * Define Model and ResourceModel
     */
    protected function _construct()
    {
        $this->_init(
            'Springbot\Queue\Model\Job',
            'Springbot\Queue\Model\ResourceModel\Job'
        );
    }
}
