<?php

namespace Springbot\Queue\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

/**
 * Class Data
 *
 * @package Springbot\Queue\Helper
 */
class Data extends AbstractHelper
{
    protected $_attributes = [];
    protected $_productFactory;

    /**
     * Data constructor.
     *
     * @param Context $context
     * @param ProductFactory $productFactory
     */
    public function __construct(
        Context $context,
        ProductFactory $productFactory
    ) {
        $this->_queueFactory = $queueFactory;
        $this->_productFactory = $productFactory;
        parent::__construct($context);
    }


}
