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
class GlassOnion_View_Helper_PrimaryButton extends GlassOnion_View_Helper_Button
{
    /**
     * Generates a HTML anchor element
     *
     * @return GlassOnion_View_Helper_HtmlAnchor
     */
    public function primaryButton($href, $label = null, $attribs = array())
    {
        $attribs['class'] = isset($attribs['class'])
            ? "{$attribs['class']} primary" : 'primary';
        return parent::button($href, $label, $attribs);
    }
}


