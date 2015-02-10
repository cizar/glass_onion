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
class GlassOnion_Controller_Action_Helper_RedirectToPreviousUri
  extends Zend_Controller_Action_Helper_Abstract
{
  const DEFAULT_NAMESPACE = __CLASS__;

  /**
   * @var string
   */
  protected $_namespace;

  /**
   * @var Zend_Session_Namespace
   */
  protected $_session;

  /**
   * @return string
   */
  public function getNamespace()
  {
    if (null == $this->_namespace) {
      $this->_namespace = self::DEFAULT_NAMESPACE;  
    }
    return $this->_namespace;
  }

  /**
   * @return GlassOnion_Controller_Action_Helper_RedirectToPreviousUri
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
   * @return GlassOnion_Controller_Action_Helper_RedirectToPreviousUri
   */
  public function setSession(Zend_Session_Namespace $session)
  {
    $this->_session = $session;
    return $this;
  }

  /**
   * @return boolean
   */
  public function hasPreviousUri()
  {
    $session = $this->getSession();
    return isset($session->previousUri);
  }

  /**
   * @return string
   */
  public function popPreviousUri()
  {
    $session = $this->getSession();
    if (!isset($session->previousUri)) {
      return null;
    }
    $previousUri = $session->previousUri;
    unset($session->previousUri);
    return $previousUri;
  }

  /**
   * @param string
   * @return GlassOnion_Controller_Action_Helper_RedirectToPreviousUri
   */
  public function storeUri($uri)
  {
    $session = $this->getSession();
    $session->previousUri = $uri;
    return $this;
  }

  /**
   * @param Zend_Controller_Request_Abstract
   * @return GlassOnion_Controller_Action_Helper_RedirectToPreviousUri
   */
  public function storeRequestUri(Zend_Controller_Request_Abstract $request = null)
  {
    if (null == $request) {
      $request = Zend_Controller_Front::getInstance()->getRequest();
    }
    $this->storeUri($request->getRequestUri());
    return $this;
  }

  /**
   * @return void
   */
  public function redirect()
  {
    $previousUri = $this->hasPreviousUri() ? $this->popPreviousUri() : '/';
    Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector')->gotoUrl($previousUri);
  }

  /**
   * @return void
   */
  public function direct()
  {
    $this->redirect();
  }
}