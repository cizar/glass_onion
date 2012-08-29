<?php

/**
 * @see Zend_Filter_Interface
 */
require_once 'Zend/Filter/Interface.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_Filter
 */
class GlassOnion_Filter_UnderscoreCase implements Zend_Filter_Interface
{
    /**
     * Defined by Zend_Filter_Interface
     *
     * Converts a camel case string into underscored
     *
     * @param string $value
     * @return string
     */
    public function filter($value)
    {
        return strtolower(preg_replace('/([^A-Z])([A-Z])/', '$1_$2', $value)); 
    }
}
