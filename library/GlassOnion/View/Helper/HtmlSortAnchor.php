<?php

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'GlassOnion/View/Helper/HtmlAnchor.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_View
 * @subpackage Helper
 */
class GlassOnion_View_Helper_HtmlSortAnchor
    extends Zend_View_Helper_Abstract
{
    /**
     * Defined by Zend_Application_Resource_Resource
     *
     * @param string $href
     * @return GlassOnion_View_Helper_HtmlAnchor
     */
    public function htmlSortAnchor($label, $field, $order = 'asc',
        $name = null, $reset = false, $encode = true)
    {
        return $this->view->htmlAnchor(
            $this->view->sortUrl($field, $order, $name, $reset, $encode), $label);
    }
}
