<?php

/**
 * @see GlassOnion_View_Helper_Button
 */
require_once 'GlassOnion/View/Helper/SupportingButton.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_View
 * @subpackage Helper
 */
class GlassOnion_View_Helper_BackButton extends GlassOnion_View_Helper_SupportingButton
{
    /**
     * Generates a HTML anchor element
     *
     * @return GlassOnion_View_Helper_HtmlAnchor
     */
    public function backButton($label = null, $attribs = array())
    {
        return parent::supportingButton('javascript:history.back(1)', $label, $attribs);
    }
}


