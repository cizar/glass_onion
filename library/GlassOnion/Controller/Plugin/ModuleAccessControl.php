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
 * @see Zend_Controller_Plugin_Abstract
 */
require_once 'Zend/Controller/Plugin/Abstract.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_Controller
 * @subpackage Plugin
 */
class GlassOnion_Controller_Plugin_ModuleAccessControl
    extends Zend_Controller_Plugin_Abstract
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
     * @return GlassOnion_Controller_Plugin_ModuleAccessControl Provides a fluent interface
     */
    public function setModuleName($moduleName)
    {
        $this->_moduleName = $moduleName;
        return $this;
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
     * @return GlassOnion_Controller_Plugin_ModuleAccessControl Provides a fluent interface
     */
    public function setDeniedAction($action, $controller, $module = null)
    {
        $this->_deniedAction = array(
            'module' => $module,
            'controller' => $controller,
            'action' => $action);
        return $this;
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
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
        $module = $request->getModuleName();

        $hasIdentity = Zend_Auth::getInstance()->hasIdentity();

        if (!$hasIdentity and $module == $this->getModuleName()) {
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

        if ($deniedAction['module']) {
            $this->_request->setModuleName($deniedAction['module']);
        }

        if ($deniedAction['controller']) {
            $this->_request->setControllerName($deniedAction['controller']);
        }

        if ($deniedAction['action']) {
            $this->_request->setActionName($deniedAction['action']);
        }
    }
}
