<?php

/**
 * Glass Onion
 *
 * Copyright (c) 2009 César Kästli (cesarkastli@gmail.com)
 *
 * Permission is hereby granted, free of charge, to any
 * person obtaining a copy of this software and associated
 * documentation files (the "Software"), to deal in the
 * Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the
 * Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice
 * shall be included in all copies or substantial portions of
 * the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY
 * KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR
 * PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS
 * OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR
 * OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
 * OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * @copyright  Copyright (c) 2009 César Kästli (cesarkastli@gmail.com)
 * @license    MIT
 */

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
     * Default denied page
     */
    const DEFAULT_DENIED_PAGE_ACTION = 'login';
    const DEFAULT_DENIED_PAGE_CONTROLLER = 'session';
    const DEFAULT_DENIED_PAGE_MODULE = 'default';

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
     * Sets the current role name by a given role
     *
     * @param Zend_Acl_Role_Interface $role
     * @return GlassOnion_Controller_Plugin_AccessControl Provides a fluent interface
     */
    public function setRole(Zend_Acl_Role_Interface $role)
    {
        $this->setRoleName($role->getRoleId());
        return $this;
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
        if (is_array($action) and count($action) == 3) {
            list($action, $controller, $module) = $action;
        }
        $page = new stdClass();
        $page->actionName = is_null($action) ? self::DEFAULT_DENIED_PAGE_ACTION : $action;
        $page->controllerName = is_null($controller) ? self::DEFAULT_DENIED_PAGE_CONTROLLER : $controller;
        $page->moduleName = is_null($module) ? self::DEFAULT_DENIED_PAGE_MODULE : $module;
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
            $page = new stdClass();
            $page->actionName = self::DEFAULT_DENIED_PAGE_ACTION;
            $page->controllerName = self::DEFAULT_DENIED_PAGE_CONTROLLER;
            $page->moduleName = self::DEFAULT_DENIED_PAGE_MODULE;
            $this->_accessDeniedPage = $page;
        }
        return $this->_accessDeniedPage;
    }

    /**
     * Returns true if the current page is the access denied page
     *
     * @return boolean
     */
    public function isAccessDeniedPage()
    {
        $request = $this->getRequest();
        $accessDeniedPage = $this->getAccessDeniedPage();
        if ($accessDeniedPage->actionName == $request->getActionName()
            && $accessDeniedPage->controllerName == $request->getControllerName()
            && $accessDeniedPage->moduleName == $request->getModuleName()) {
            return true;
        }
        return false;
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

        if (!empty($moduleName) && Zend_Controller_Front::getInstance()->getDefaultModule() != $moduleName) {
            return $moduleName . '::' . $controllerName;
        }

        return $controllerName;
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
        if ($this->isAccessDeniedPage()) {
            return;
        }

        if ($this->getAcl()->isAllowed($this->getRoleName(), $this->getResourceName(), $request->getActionName())) {
            return;
        }

        Zend_Controller_Action_HelperBroker::getStaticHelper('RedirectToPreviousUri')->storeRequestUri();

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
            ->setDispatched(false)
            ->setModuleName($deniedPage->moduleName)
            ->setControllerName($deniedPage->controllerName)
            ->setActionName($deniedPage->actionName);
    }
}