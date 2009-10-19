<?php

require_once ('Zend/Controller/Plugin/Abstract.php');

class GlassOnion_Controller_Plugin_AccessControl extends Zend_Controller_Plugin_Abstract
{
	private $_acl = null;
	
	public function setAcl(Zend_Acl $acl)
	{
		$this->_acl = $acl;
	}
	
	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{
		$resource = $request->getControllerName();
		
		$module = $request->getModuleName();
		
		$defaultModule = Zend_Controller_Front::getInstance()->getDefaultModule(); 
		
		if ($module != $defaultModule)
		{
			$resource = $module . '/' . $resource;
		}
		
		$action = $request->getActionName();
		
		$role = Zend_Auth::getInstance()->hasIdentity() ? 'authenticated' : 'anonymous';
		
		if (!$this->_acl->has($resource) or !$this->_acl->isAllowed($role, $resource, $action))
		{
			$request->setModuleName('default')
                ->setControllerName('session')
                ->setActionName('login');
		}
	}
}