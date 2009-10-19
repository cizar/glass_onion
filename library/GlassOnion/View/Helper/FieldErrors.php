<?php

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
        $fieldErrors = $record->getErrorStack()->get((string) $field);
        
        if (is_null($fieldErrors))
        {
           return '';
        }
        
        return $this->view->htmlList($fieldErrors);
    }
}
