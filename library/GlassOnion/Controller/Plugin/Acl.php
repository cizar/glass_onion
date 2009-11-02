<?php

/**
 * @see Zend_Acl
 */
require_once 'Zend/Acl.php';

/**
 * @see Zend_Controller_Plugin_Abstract
 */
require_once 'Zend/Controller/Plugin/Abstract.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_Controller_Plugin_Acl
 */
final class GlassOnion_Controller_Plugin_Acl extends Zend_Controller_Plugin_Abstract
{
	/**
	 * @var Zend_Acl
	 */
	protected $_acl;

	/**
	 * @var array
	 */
	protected $_deniedAction;

	/**
	 * Constructor
	 *
	 * @param mixed $acl
	 * @return void
	 */
	public function __construct(Zend_Acl $acl)
	{
		$this->setDeniedAction('login', 'session', 'default');

		$this->setAcl($acl);
	}

	/**
	 * Returns the ACL object
	 *
	 * @return Zend_Acl
	 */
	public function getAcl()
	{
		return $this->_acl;
	}

	/**
	 * Sets the ACL object
	 *
	 * @param Zend_Acl $acl
	 * @return void
	 */
	public function setAcl(Zend_Acl $acl)
	{
		$this->_acl = $acl;
	}

	/**
	 * Sets the denied action
	 *
	 * @param string $action
	 * @param string $controller
	 * @param string $module
	 * @return void
	 */
	public function setDeniedAction($action, $controller = 'error', $module = null)
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
	 * Returns the resource action
	 *
	 * @return array
	 */
	public function getResourceName()
	{
		$controllerName = $this->_request->getControllerName();

		$moduleName = $this->_request->getModuleName();

		if ($moduleName == Zend_Controller_Front::getInstance()->getDefaultModule())
		{
			return $controllerName;
		}

		return $moduleName . ':' . $controllerName;
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
		$resourceName = $this->getResourceName();

		$acl = $this->getAcl();

		if (!$acl->has($resourceName))
		{
			throw new Exception('Unknown resource ' . $resourceName);
		}

		if (!$acl->isAllowed($this->_getRoleName(), $resourceName, $request->getActionName()))
		{
			Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger')
				->setNameSpace('GlassOnion_Return_Url')
				->addMessage($request->getRequestUri());

			$this->denyAccess();
		}
	}

	/**
	 * Deny Access Function
	 * Redirects to deniedAction
	 *
	 * @return void
	 */
	public function denyAccess()
	{
		$deniedAction = $this->getDeniedAction();

		$this->_request
			->setModuleName($deniedAction['module'])
			->setControllerName($deniedAction['controller'])
			->setActionName($deniedAction['action']);
	}
	
	private function _getRoleName()
	{
		$auth = Zend_Auth::getInstance();

		if (!$auth->hasIdentity())
		{
			return 'guest';
		}

		return $auth->getIdentity();
	}
}
