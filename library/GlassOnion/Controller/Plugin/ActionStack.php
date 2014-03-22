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
 * @category   InAdvant
 * @package    InAdvant_Controller
 * @subpackage Plugin
 */
class GlassOnion_Controller_Plugin_ActionStack
    extends Zend_Controller_Plugin_Abstract
{
    /**
     * @var array
     */
    private $_requests = array();

    /**
     * Appends an action to be pushed to the ActionStack
     *
     * @param string $action
     * @param string $controller
     * @param string $module
     * @param array $params
     * @return GlassOnion_Controller_Plugin_ActionStack Provides a fluent interface
     */
    public function pushToStack($action, $controller, $module = null, array $params = array())
    {
        $this->_requests[] = array($action, $controller, $module, $params);
    }

    /**
     * Before the dispatch loop, push some actions into the stack
     *
     * @param Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
        if ($request->isXmlHttpRequest()) {
            return;
        }
        $plugin = $this->getActionStackPlugin();
        foreach ($this->_requests as $request) {
            list($action, $controller, $module, $params) = $request;
            $plugin->pushStack(new Zend_Controller_Request_Simple($action,
                $controller, $module, $params));
        }
    }

    /**
     * Returns an instance of the action stack plugin
     *
     * @return Zend_Controller_Plugin_ActionStack
     */
    public function getActionStackPlugin()
    {
        $front = Zend_Controller_Front::getInstance();
        
        if ($front->hasPlugin('Zend_Controller_Plugin_ActionStack')) {
            $plugin = $front->getPlugin('Zend_Controller_Plugin_ActionStack');
        } else {
            /**
             * @see Zend_Controller_Plugin_ActionStack
             */
            require_once 'Zend/Controller/Plugin/ActionStack.php';
            $plugin = new Zend_Controller_Plugin_ActionStack();
            $front->registerPlugin($plugin);
        }
        
        return $plugin;
    }
}