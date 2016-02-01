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
class GlassOnion_Controller_Action_Helper_Resource
  extends Zend_Controller_Action_Helper_Abstract
{
  /**
   * Returns an application resource
   *
   * @return Zend_Application_Resource_ResourceAbstract
   */
  public function direct($name)
  {
    return Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource($name);
  }
}
