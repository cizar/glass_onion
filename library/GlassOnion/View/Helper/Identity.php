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
class GlassOnion_View_Helper_Identity
    extends Zend_View_Helper_Abstract
{
    /**
     * Returns the identity of the authenticated user
     *
     * @param string $field
     * @return mixed
     */
    public function identity($field = null)
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        return is_null($field) ? $identity : $identity->$field;
    }
}