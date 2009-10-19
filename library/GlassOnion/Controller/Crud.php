<?php

require_once 'Zend/Controller/Action.php';

abstract class GlassOnion_Controller_Crud extends Zend_Controller_Action
{
    abstract public function indexAction();
    abstract public function showAction();
    abstract public function newAction();
    abstract public function editAction();
    abstract public function deleteAction();
}