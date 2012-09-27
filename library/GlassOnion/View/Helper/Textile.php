<?php

/**
 * @see GlassOnion_View_Helper_Markup
 */
require_once 'GlassOnion/View/Helper/Markup.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_View
 * @subpackage Helper
 */
class GlassOnion_View_Helper_Textile extends GlassOnion_View_Helper_Markup
{
    /**
     * Renders a text usign Zend_Markup
     *
     * @return GlassOnion_View_Helper_Textile Provides a fluent interface
     */
    public function textile($value, $parser = null)
    {
        return parent::markup($value, 'Textile');
    }
}
