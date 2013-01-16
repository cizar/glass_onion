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
     * Add a formated message to the flash messenger
     *
     * @param string $method
     * @param array $args
     * @return void
     */
    public function __call($method, $args)
    {
        if (!preg_match('/^(success|info|warning|error)$/', $method)) {
            throw new Exception("Status '$method' is not allowed");
        }
        foreach ($args as $message) {
            $this->addMessage(array('status' => $method, 'message' => $message));
        }
    }
}