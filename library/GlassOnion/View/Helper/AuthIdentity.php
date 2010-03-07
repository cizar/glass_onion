<?php

require_once 'Zend/View/Helper/Abstract.php';

class GlassOnion_View_Helper_AuthIdentity extends Zend_View_Helper_Abstract
{
    public function authIdentity($field = null)
    {
        $identity = Zend_Auth::getInstance()
            ->getIdentity();

        return is_null($field) ? $identity : $identity->$field;
    }
}
