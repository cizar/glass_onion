<?php

/**
 * @see Zend_Controller_Action
 */
require_once 'Zend/Controller/Action.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_Controller
 */
abstract class GlassOnion_Controller_Crud_Abstract
    extends Zend_Controller_Action
{
    abstract public function indexAction();
    abstract public function showAction();
    abstract public function newAction();
    abstract public function editAction();
    abstract public function deleteAction();
}
