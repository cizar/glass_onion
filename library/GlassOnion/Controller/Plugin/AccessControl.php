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
 * @package    GlassOnion_Controller
 * @subpackage Plugin
 */
class GlassOnion_Controller_Plugin_AccessControl
    extends Zend_Controller_Plugin_Abstract
{
    /**
     * @var Zend_Acl
     */
    protected $_acl;

    /**
     * @var string
     */
    protected $_roleName;

    /**
     * @var array
     */
    protected $_accessDeniedPage;

    /**
     * Sets the ACL object
     *
     * @param Zend_Acl $acl
     * @return GlassOnion_Controller_Plugin_AccessControl Provides a fluent interface
     */
    public function setAcl(Zend_Acl $acl)
    {
        $this->_acl = $acl;
        return $this;
    }

    /**
     * Returns the ACL object
     *
     * @return Zend_Acl
     */
    public function getAcl()
    {
        if (null == $this->_acl) {
            $this->_acl = Zend_Registry::get('Zend_Acl');
        }
        return $this->_acl;
    }

    /**
     * Sets the current role name
     *
     * @param string $roleName
     * @return GlassOnion_Controller_Plugin_AccessControl Provides a fluent interface
     */
    public function setRoleName($roleName)
    {
        $this->_roleName = $roleName;
        return $this;
    }

    /**
     * Returns the current role name
     *
     * @return string
     */
    public function getRoleName()
    {
        if (null == $this->_roleName) {
            $auth = Zend_Auth::getInstance();
            $this->_roleName = $auth->hasIdentity() ? $auth->getIdentity() : 'guest';
        }
        return $this->_roleName;
    }

    /**
     * @return boolean
     */
    public function isAccessDeniedPage()
    {
        $request = $this->getRequest();
        $deniedPage = $this->getAccessDeniedPage();
        if ($request->getModuleName() !== $deniedPage->moduleName
            || $request->getControllerName() !== $deniedPage->controllerName
            || $request->getActionName() !== $deniedPage->actionName) {
            return false;
        }
        return true;
    }

    /**
     * Sets the access denied page
     *
     * @param string $action
     * @param string $controller
     * @param string $module
     * @return GlassOnion_Controller_Plugin_AccessControl Provides a fluent interface
     */
    public function setAccessDeniedPage($action, $controller = null, $module = null)
    {
        $page = new stdClass();
        $page->actionName = $action;
        $page->controllerName = $controller;
        $page->moduleName = $module;
        $this->_accessDeniedPage = $page;
        return $this;
    }

    /**
     * Returns the access denied page
     *
     * @return array
     */
    public function getAccessDeniedPage()
    {
        if (null == $this->_accessDeniedPage) {
            $this->setAccessDeniedPage('login', 'session', 'default');
        }
        return $this->_accessDeniedPage;
    }

    /**
     * Returns the resource action
     *
     * @return array
     */
    public function getResourceName()
    {
        $request = $this->getRequest();
        $moduleName = $request->getModuleName();
        $controllerName = $request->getControllerName();

        if (empty($moduleName) || Zend_Controller_Front::getInstance()->getDefaultModule() == $moduleName) {
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

        if (!$acl->has($resourceName)) {
            $resourceName = 'unknown';
        }

        $session = new Zend_Session_Namespace('LastRequest');

        if ($acl->isAllowed($this->getRoleName(), $resourceName, $request->getActionName())) {
            return;
        }

        Zend_Controller_Action_HelperBroker::getStaticHelper('LastRequest')->rememberCurrentRequest();

        $this->denyAccess();
    }

    /**
     * Deny Access Function
     * Redirects to the access denied page
     *
     * @return void
     */
    public function denyAccess()
    {
        $deniedPage = $this->getAccessDeniedPage();

        $this->_request
            ->setDispatched(FALSE)
            ->setModuleName($deniedPage->moduleName)
            ->setControllerName($deniedPage->controllerName)
            ->setActionName($deniedPage->actionName);
    }
}