<?php

/**
 * @see Zend_Controller_Action_Helper_Abstract
 */
require_once 'Zend/Controller/Action/Helper/Abstract.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_Controller
 * @subpackage Helper
 */
class GlassOnion_Controller_Action_Helper_Identity
    extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * Returns the identity of the authenticated user
     *
     * @param string $field
     * @return mixed
     */
    public function direct($field = null)
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        if (null !== $field) {
            if (!is_object($identity) || !property_exists($identity, $field)) {
                throw new Exception("Unable to get the '$field' property from the identity");
            }
            return $identity->$field;
        }
        return $identity;
    }
}
