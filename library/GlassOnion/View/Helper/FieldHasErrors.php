<?php

/**
 * @see Zend_View_Helper_FormElement
 */
require_once 'Zend/View/Helper/FormElement.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_View
 * @subpackage Helper
 */
class GlassOnion_View_Helper_FieldHasErrors extends Zend_View_Helper_FormElement
{
    /**
     * Returns true if the field has errors (Only for Doctrine)
     *
     * @param string $field
     * @param Doctrine_Record $record
     * @return boolean
     */
    public function fieldHasErrors($field, Doctrine_Record $record = null)
    {
        if (null === $record) {
            $record = $this->view->record;
        }
        return $record->getErrorStack()->contains((string) $field);
    }
}
