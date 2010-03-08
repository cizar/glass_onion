<?php

/**
 * @see Zend_Controller_Action_Helper_Abstract
 */
require_once 'Zend/Controller/Action/Helper/Abstract.php';

class GlassOnion_Controller_Action_Helper_AuthIdentity extends Zend_Controller_Action_Helper_Abstract
{
	public function direct($field = null)
	{
		$identity = Zend_Auth::getInstance()
			->getIdentity();

		return is_null($field) ? $identity : $identity->$field;
	}
}