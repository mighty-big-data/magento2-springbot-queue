<?php

namespace Springbot\Queue\Controller\Queue;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Springbot\Queue\Helper\Data;

/**
 * Class Index
 *
 * @package Springbot\Queue\Controller\Queue
 */
class Index extends Action
{
    /**
     * @var Http
     */
    protected $_request;

    /**
     * @var Data
     */
    protected $_data;

    /**
     * @var Json
     */
    protected $_json;

    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;


    /**
     * Index constructor.
     *
     * @param Context $context
     * @param Http $request
     * @param Json $json
     * @param ScopeConfigInterface $config
     */
    public function __construct(
        Context $context,
        Data $data,
        Http $request,
        Json $json,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->_data = $data;
        $this->_request = $request;
        $this->_json = $json;
        $this->_scopeConfig = $scopeConfig;
        parent::__construct($context);
    }


    /**
     * @return array
     */
    public function execute()
    {
        $token = $this->_request->getHeader('Springbot-Security-Token');
        $params = $this->_request->getParams();

        if ($this->_data->authenticate($token)) {
            if ($params['task'] === 'view_config') {
                return $this->_json->setData($this->_scopeConfig->getValue('springbot'));
            } else {
                return $this->_json->setData(['error' => 'Not a valid task']);
            }
        } else {
            return $this->_json->setData(['error' => '401 Unauthorized']);
        }
    }
}
