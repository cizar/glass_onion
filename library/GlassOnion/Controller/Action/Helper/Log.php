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
class GlassOnion_Controller_Action_Helper_Log extends Zend_Controller_Action_Helper_Abstract
{
	/**
	 * @return void
	 */
	public function direct($message, $priority = Zend_Log::INFO)
	{
		$bootstrap = $this->getActionController()->getInvokeArg('bootstrap')
			->getResource('logger')->log($message, $priority);
	}
}
