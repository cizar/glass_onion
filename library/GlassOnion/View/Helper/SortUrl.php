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
class GlassOnion_View_Helper_SortUrl
    extends Zend_View_Helper_Abstract
{
    /**
     * Generates the sort url for a given field in a sort condition
     *
     * @param string $field
     * @param string|array $data
     * @param string $name
     * @param boolean $reset
     * @param boolean $encode
     * @return string
     */
    public function sortUrl($field, $data = null,
        $name = null, $reset = true, $encode = true)
    {
        if (null === $data && isset($this->view->sortData)) {
            $data = $this->view->sortData;
        }
        
        switch (gettype($data))
        {
            case 'string':
                $order = $data;
                break;
                
            case 'array':
                if ($field === $data['field']) {
                    $order = $this->_reverse($data['order']);
                    break;
                }
                // NO break.. go to default
                
            case 'NULL':
                $order = 'asc';
        }
        
        $options = array('sort' => $order . 'ending_by_' . $field);

        return $this->view->url($options, $name, $reset, $encode);
    }
    
    /**
     * Returns the reverse order of a given order
     *
     * @param string $order [asc|desc]
     * @return string
     */
    private function _reverse($order)
    {
        return ('asc' === $order) ? 'desc' : 'asc';
    }
}
