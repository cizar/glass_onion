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
  private $_options;
  
  /**
   * Constructor
   *
   * @param array $options
   * @return void
   */
  public function __construct($options = null)
  {
    if (null !== $options) {
      $this->setOptions($options);
    }
  }

  /**
   * Sets the options
   *
   * @param array $options
   * @return GlassOnion_Controller_Plugin_ActionStack Provides fluent interface
   */
  public function setOptions($options)
  {
    $this->_options = $options;
    return $this;
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
    $actionStack = Zend_Controller_Action_HelperBroker::getStaticHelper('actionStack');
    foreach ($this->_options as $id => $config) {
      $data = $this->_parseActionConfig($config);
      $data['params']['_actionStackId'] = $id;
      $actionStack->actionToStack($data['action'], $data['controller'], $data['module'], $data['params']);
    }
  }

  /**
   * Parse the config
   *
   * @param array $config
   * @return array
   */
  private function _parseActionConfig($config)
  {
    $data = array('action' => null, 'controller' => null, 'module' => null, 'params' => array());
    if (isset($config['action'])) {
      $data['action'] = $config['action'];
      unset($config['action']);
    }
    if (isset($config['controller'])) {
      $data['controller'] = $config['controller'];
      unset($config['controller']);
    }
    if (isset($config['module'])) {
      $data['module'] = $config['module'];
      unset($config['module']);
    }
    if (is_array($config)) {
      $data['params'] = $config;
    }
    return $data;
  }
}