<?php

namespace Springbot\Queue\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

/**
 * Class Data
 *
 * @package Springbot\Queue\Helper
 */
class Data extends AbstractHelper
{
    const BLANK_HASH = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
    
    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var array
     */
    protected $_attributes = [];

    /**
     * Data constructor.
     *
     * @param Context $context
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(Context $context, ScopeConfigInterface $scopeConfig)
    {
        $this->_scopeConfig = $scopeConfig;
        parent::__construct($context);
    }


    /**
     * Get store config
     * 
     * @return array
     */
    public function getConfig()
    {
        return $this->_scopeConfig->getValue('springbot');
    }
}
