<?php

require_once ('Zend/Controller/Plugin/Abstract.php');

class GlassOnion_Controller_Plugin_ModuleAccessControl extends Zend_Controller_Plugin_Abstract
{
	/**
	 * @var Zend_Acl
	 */
	private $_moduleName = 'default';

	/**
	 * @var array
	 */
	private $_deniedAction = array('controller' => 'session', 'action' => 'login');

	/**
	 * Sets the name of the module thats requires authentification
	 *
	 * @param string $moduleName
	 * @return void
	 */
	public function setModuleName($moduleName)
	{
		$this->_moduleName = $moduleName;
	}

	/**
	 * Returns module name
	 *
	 * @return string
	 */
	public function getModuleName()
	{
		return $this->_moduleName;
	}

	/**
	 * Sets the denied action
	 *
	 * @param string $action
	 * @param string $controller
	 * @param string $module
	 * @return void
	 */
	public function setDeniedAction($action, $controller, $module = null)
	{
		$this->_deniedAction = array(
			'module' => $module,
			'controller' => $controller,
			'action' => $action);
	}

	/**
	 * Returns the denied action
	 *
	 * @return array
	 */
	public function getDeniedAction()
	{
		return $this->_deniedAction;
	}

	/**
	 * Predispatch
	 * Checks if the current user identified by roleName has rights to the requested url (module/controller/action)
	 * If not, it will call denyAccess to be redirected to deniedAction
	 *
	 * @param Zend_Controller_Request_Abstract $request
	 * @return void
	 */
	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{
		$module = $request->getModuleName();

		$hasIdentity = Zend_Auth::getInstance()->hasIdentity();

		if (!$hasIdentity and $module == $this->getModuleName())
		{
			Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger')
				->setNameSpace('GlassOnion_Return_Url')
				->addMessage($request->getRequestUri());

			$this->denyAccess();
		}
	}

	/**
	 * Deny Access Function
	 * Redirects to the deniedAction's action
	 *
	 * @return void
	 */
	public function denyAccess()
	{
		$deniedAction = $this->getDeniedAction();

		if ($deniedAction['module'])
		{
			$this->_request->setModuleName($deniedAction['module']);
		}

		if ($deniedAction['controller'])
		{
			$this->_request->setControllerName($deniedAction['controller']);
		}

		if ($deniedAction['action'])
		{
			$this->_request->setActionName($deniedAction['action']);
		}
	}
}
