<?php

/**
 * @see Zend_Controller_Action_Helper_Abstract
 */
require_once 'Zend/Controller/Action/Helper/Abstract.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_Controller
 * @subpackage Helper
 */
class GlassOnion_Controller_Action_Helper_Log
    extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * @var Zend_Log
     */
    private $_logger = null;
    
	/**
     * Returns the logger and logs a message if it's defined
     *
     * @param string $message
     * @param int $priority
	 * @return Zend_Log
	 */
	public function direct($message = null, $priority = Zend_Log::INFO)
	{
        $logger = $this->getLogger();
        if (null !== $message)
        {
            $logger->log($message, $priority);
        }
        return $logger;
	}

    /**
     * Returns the logger
     *
     * @return Zend_Log
     */
    public function getLogger()
    {
        if (null !== $this->_logger)
        {
            return $this->_logger;
        }
        
        $bootstrap = $this->getActionController()
            ->getInvokeArg('bootstrap');

        if ($bootstrap->hasResource('log'))
        {
            return $bootstrap->getResource('log');
        }

        /**
         * @see Zend_Log
         */
        require_once 'Zend/Log.php';
        $this->_logger = new Zend_Log(new Zend_Log_Writer_Null());

        return $this->_logger;
    }
}
