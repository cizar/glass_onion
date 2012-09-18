<?php

/**
 * @see GlassOnion_View_Helper_Button
 */
require_once 'GlassOnion/View/Helper/Button.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_View
 * @subpackage Helper
 */
class GlassOnion_View_Helper_BackButton extends GlassOnion_View_Helper_Button
{
    /**
     * Generates a HTML anchor element
     *
     * @return GlassOnion_View_Helper_HtmlAnchor
     */
    public function backButton($label, $attribs = array())
    {
        return parent::button('javascript:history.back(1)', $label, $attribs);
    }
}


