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
class GlassOnion_Controller_Action_Helper_RedirectToLastRequest
    extends Zend_Controller_Action_Helper_Abstract
{
	/**
	 * @var string
	 */
	protected $_namespace = __CLASS__;

	/**
	 * @var Zend_Session_Namespace
	 */
	protected $_session = null;

	/**
	 * @return string
	 */
	public function getNamespace()
	{
		return $this->_namespace;
	}

	/**
	 * @return GlassOnion_Controller_Action_Helper_LastRequest
	 */
	public function setNamespace($namespace)
	{
		$this->_namespace = $namespace;
		return $this;
	}

	/**
	 * @return Zend_Session_Namespace
	 */
	public function getSession()
	{
		if (null == $this->_session) {
			$this->_session = new Zend_Session_Namespace($this->getNamespace());
		}
		return $this->_session;
	}

	/**
	 * @return GlassOnion_Controller_Action_Helper_LastRequest
	 */
	public function setSession(Zend_Session_Namespace $session)
	{
		$this->_session = $session;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function hasUri()
	{
		$session = $this->getSession();
		return isset($session->uri);
	}

	/**
	 * @return string
	 */
	public function pollUri()
	{
		$uri = $this->getUri();
		$this->reset();
		return $uri;
	}

	/**
	 * @return string
	 */
	public function getUri()
	{
		$session = $this->getSession();
		return $session->uri;
	}

	/**
	 * @return GlassOnion_Controller_Action_Helper_LastRequest
	 */
	public function setUri($uri)
	{
		$session = $this->getSession();
		$session->uri = $uri;
		return $this;
	}

	/**
	 * @return GlassOnion_Controller_Action_Helper_LastRequest
	 */
	public function rememberCurrentRequest(Zend_Controller_Request_Abstract $request = null)
	{
		if (null == $request) {
			$request = $this->getRequest();
		}
		return $this->setUri($request->getRequestUri());
	}

	/**
	 * @return GlassOnion_Controller_Action_Helper_LastRequest
	 */
	public function reset()
	{
		$session = $this->getSession();
		unset($session->uri);
		return $this;
	}

	/**
	 * @return void
	 */
	public function redirect()
	{
		$uri = $this->hasUri() ? $this->getUri() : '/';
		Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector')->gotoUrl($uri);
	}

	/**
	 * @return void
	 */
	public function direct()
	{
		$this->redirect();
	}
}