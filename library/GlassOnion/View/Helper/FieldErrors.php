<?php

/**
 * @see Zend_View_Helper_FormElement
 */
require_once 'Zend/View/Helper/FormElement.php';

class GlassOnion_View_Helper_FieldErrors extends Zend_View_Helper_FormElement
{
    /**
     * Returns a form label with the record error stack (Only for Doctrine)
     *
     * @param string $field
     * @param Doctrine_Record $record
     * @return Zend_View_Helper_FormLabel
     */
    public function fieldErrors($field, Doctrine_Record $record)
    {
        $errors = $record->getErrorStack()->get($field);

        if (!$errors) {
            return '';
        }

        return $this->view->doctrineErrors($errors);
    }
}
