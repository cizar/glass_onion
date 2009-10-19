<?php

require_once ('Zend/Controller/Plugin/Abstract.php');

class GlassOnion_Controller_Plugin_AccessControlAdmin extends Zend_Controller_Plugin_Abstract
{
	private static $_adminModuleName = 'admin';
	
	public static function setAdminModuleName($moduleName)
	{
		self::$_adminModuleName = $moduleName;
	}
	
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
    	//if ($request->getControllerName() == 'login' OR $request->getControllerName() == 'error')
        //    return; 
            
        $module = $request->getModuleName();
        
        $hasIdentity = Zend_Auth::getInstance()->hasIdentity();
        
        if (!$hasIdentity and $module == self::$_adminModuleName)
        {
        	Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger')
                ->setNameSpace('GlassOnion_Return_Url')
                ->addMessage($request->getRequestUri());
                
            $request->setModuleName('default')
                ->setControllerName('session')
                ->setActionName('login');
        }
    }
}