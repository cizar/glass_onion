<?php

/**
 * @see Zend_Controller_Plugin_Abstract
 */
require_once 'Zend/Controller/Plugin/Abstract.php';

class GlassOnion_Controller_Plugin_ModularLayout extends Zend_Controller_Plugin_Abstract
{
	public function routeShutdown(Zend_Controller_Request_Abstract $request)
	{
		Zend_Layout::getMvcInstance()->setLayout($request->getModuleName());
	}
}