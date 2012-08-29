<?php

/**
 * @see Zend_Filter_Interface
 */
require_once 'Zend/Filter/Interface.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_Filter
 */
class GlassOnion_Filter_UppercaseFirst implements Zend_Filter_Interface
{
    /**
     * Defined by Zend_Filter_Interface
     *
     * Returns the string $value, converting the first character to uppercase
     *
     * @param string $value
     * @return string
     */
    public function filter($value)
    {
        return ucfirst($value);
    }
}
