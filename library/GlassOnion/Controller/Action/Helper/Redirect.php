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
 * @see Zend_Controller_Action_Helper_Abstract
 */
require_once 'Zend/Controller/Action/Helper/Abstract.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_Controller
 * @subpackage Helper
 */
class GlassOnion_Controller_Action_Helper_Redirect
  extends Zend_Controller_Action_Helper_Abstract
{
  /**
   * Simple redirector for normal or AJAX requests
   *
   * @param string $action
   * @param string $controller
   * @param string $module
   * @param arrau $params
   * @return string
   */
  public function direct($action, $controller = null, $module = null, array $params = array())
  {
    if ($this->getRequest()->isXmlHttpRequest()) {
      $url = $this->_helper('url')->simple($action, $controller, $module, $params);
      $this->_helper('json')->sendJson(array('redirect' => $url));
    } else {
      $this->_helper('redirector')->gotoSimple($action, $controller, $module, $params);
    }
  }

  private function _helper($helper)
  {
    return Zend_Controller_Action_HelperBroker::getStaticHelper($helper);
  }
}