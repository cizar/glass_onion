<?php

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_View
 * @subpackage Helper
 */
class GlassOnion_View_Helper_Coalesce extends Zend_View_Helper_Abstract
{
    /**
     * Returns the first not-null argument
     *
     * @return mixed
     */
    public function coalesce()
    {
        return array_shift(array_filter(func_get_args()));
    }
}

