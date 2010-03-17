<?php

require_once ('Zend/Controller/Plugin/Abstract.php');

class GlassOnion_Controller_Plugin_ModuleAccessControl extends Zend_Controller_Plugin_Abstract
{
	private static $_moduleName = 'default';
	
	protected $_deniedAction = array('controller' => 'session', 'action' => 'login');
	
	public static function setModuleName($moduleName)
	{
		self::$_moduleName = $moduleName;
	}
	
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $module = $request->getModuleName();
        
        $hasIdentity = Zend_Auth::getInstance()->hasIdentity();
        
        if (!$hasIdentity and $module == self::$_moduleName)
        {
        	Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger')
                ->setNameSpace('GlassOnion_Return_Url')
                ->addMessage($request->getRequestUri());

            $this->denyAccess();
        }
    }

	public function denyAccess()
	{
		if ($this->_deniedAction['module'])
		{
			$this->_request->setModuleName($this->_deniedAction['module']);
		}

		if ($this->_deniedAction['controller'])
		{
			$this->_request->setControllerName($this->_deniedAction['controller']);
		}

		if ($this->_deniedAction['action'])
		{
			$this->_request->setActionName($this->_deniedAction['action']);
		}
	}
}