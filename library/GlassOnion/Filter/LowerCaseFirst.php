<?php

require_once 'Zend/Filter/Interface.php';

class GlassOnion_Filter_LowerCaseFirst implements Zend_Filter_Interface
{
    public function filter($value)
    {
        $value[0] = strtolower($value[0]);
        return $value;
    }
}
