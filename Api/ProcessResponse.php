<?php

namespace Springbot\Queue\Api;

/**
 * Class ProcessResponse
 * @package Springbot\Queue\Api
 */
class  ProcessResponse implements ProcessResponseInterface
{
    public $message;
    public $count;

    /**
     * ProcessResponse constructor.
     * @param $message
     * @param $count
     */
    public function __construct($message, $count)
    {
        $this->message = $message;
        $this->count = $count;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return mixed
     */
    public function getCount()
    {
        return $this->count;
    }

}
