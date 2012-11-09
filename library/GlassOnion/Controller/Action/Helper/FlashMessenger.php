<?php

/**
 * @see Zend_Controller_Action_Helper_Abstract
 */
require_once 'Zend/Controller/Action/Helper/FlashMessenger.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_Controller
 * @subpackage Helper
 */
class GlassOnion_Controller_Action_Helper_FlashMessenger
    extends Zend_Controller_Action_Helper_FlashMessenger
{
    /**
     * Strategy pattern: proxy to addMessage()
     *
     * @param  string $message
     * @return void
     */
    public function direct($message)
    {
        return parent::direct($this->_factory($message, 'info'));
    }

    /**
     * Add a message to flash message
     *
     * @param string $method
     * @param array $args
     * @return void
     */
    public function __call($method, $args)
    {
        foreach ($args as $message) {
            $this->addMessage($this->_factory($message, $method));
        }
    }

    private function _factory($message, $status)
    {
        if (!preg_match('/^(success|info|warning|error)$/', $status)) {
            throw new Exception("Status '$status' is not allowed");
        }
        $obj = new stdClass();
        $obj->message = $message;
        $obj->status = $status;
        return $obj;
    }
}
