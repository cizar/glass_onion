<?php

class GlassOnion_View_Helper_FieldHasErrors extends Zend_View_Helper_FormElement
{
    /**
     * Returns true if the field has errors (Only for Doctrine)
     *
     * @param string $field
     * @param Doctrine_Record $record
     * @return bool
     */
    public function fieldHasErrors($field, Doctrine_Record $record)
    {
        return $record->getErrorStack()->contains((string) $field);
    }
}
