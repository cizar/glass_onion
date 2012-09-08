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
class GlassOnion_View_Helper_SortClass
    extends Zend_View_Helper_Abstract
{
    /**
     * Defined by Zend_Application_Resource_Resource
     *
     * @param string $field
     * @param array $data
     * @return string
     */
    public function sortClass($field, $data)
    {
        if ($field !== $data['field']) {
            return '';
        }
        
        return ('asc' === $data['order']) ? 'desc' : 'asc';
    }
}
