<?php

/**
 * @see Zend_View_Helper_HtmlElement
 */
require_once 'Zend/View/Helper/HtmlElement.php';

class GlassOnion_View_Helper_HtmlAnchor extends Zend_View_Helper_HtmlElement
{
	public function htmlAnchor($href, $label, $attribs = false)
	{
		if ($attribs) {
			$attribs = $this->_htmlAttribs($attribs);
		} else {
			$attribs = '';
		}

		return sprintf('<a href="%s" %s>%s</a>', $href, $attribs, $label);
	}
}