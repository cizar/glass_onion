<?php

class GlassOnion_View_Helper_HasIdentity
{
    public function hasIdentity()
    {
        return Zend_Auth::getInstance()
            ->hasIdentity();
    }
}
