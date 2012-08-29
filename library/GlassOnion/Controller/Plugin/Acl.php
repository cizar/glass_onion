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
final class GlassOnion_Controller_Plugin_Acl
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
    protected $_deniedAction;

    /**
     * Constructor
     *
     * @param mixed $acl
     * @return void
     */
    public function __construct(Zend_Acl $acl, $roleName = 'guest')
    {
        $this->setDeniedAction('login', 'session', 'default');
        $this->setAcl($acl);
        $this->setRoleName($roleName);
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
     * Returns the ACL object
     *
     * @return Zend_Acl
     */
    public function getAcl()
    {
        return $this->_acl;
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
     * Sets the current role name
     *
     * @param string $roleName
     */
    public function setRoleName($roleName)
    {
        $this->_roleName = $roleName;
    }

    /**
     * Returns the current role name
     *
     * @return string
     */
    public function getRoleName()
    {
        return $this->_roleName;
    }

    /**
     * Returns the resource action
     *
     * @return array
     */
    public function getResourceName()
    {
        $request = $this->_request;

        $moduleName = $request->getModuleName();
        $controllerName = $request->getControllerName();

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

        if (!$acl->isAllowed($this->getRoleName(), $resourceName, $request->getActionName()))
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

        $this->_request
            ->setModuleName($deniedAction['module'])
            ->setControllerName($deniedAction['controller'])
            ->setActionName($deniedAction['action']);
    }
}
