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
     * Generates a HTML anchor element
     *
     * @param string $href
     * @return GlassOnion_View_Helper_HtmlAnchor
     */
    public function htmlSortAnchor($label, $field, $order = null,
        $name = null, $reset = false, $encode = true)
    {
        return $this->view->htmlAnchor(
            $this->view->sortUrl($field, $order, $name, $reset, $encode), $label);
    }
}
