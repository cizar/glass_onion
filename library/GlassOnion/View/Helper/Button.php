<?php

/**
 * @see GlassOnion_View_Helper_HtmlAnchor
 */
require_once 'GlassOnion/View/Helper/HtmlAnchor.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_View
 * @subpackage Helper
 */
class GlassOnion_View_Helper_Button extends GlassOnion_View_Helper_HtmlAnchor
{
    /**
     * Generates a HTML anchor element
     *
     * @return GlassOnion_View_Helper_HtmlAnchor
     */
    public function button($href, $label = null, $attribs = array())
    {
        $attribs['class'] = isset($attribs['class'])
            ? "{$attribs['class']} button" : 'button';
        return $this->view->htmlAnchor($href, $label, $attribs);
    }
}


