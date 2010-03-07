<?php

require_once 'Zend/View/Helper/Abstract.php';

class GlassOnion_View_Helper_HasIdentity extends Zend_View_Helper_Abstract
{
    public function hasIdentity()
    {
        return Zend_Auth::getInstance()
            ->hasIdentity();
    }
}
