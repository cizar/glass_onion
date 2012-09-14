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
     * Returns a sort class given a field and sort condition
     *
     * @param string $field
     * @param array $data
     * @return string
     */
    public function sortClass($field, $data = null)
    {
        if (null == $data) {
            $data = $this->view->sortData;
        }
        return ($field === $data['field']) ? $data['order'] : '';
    }
}
