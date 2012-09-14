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
class GlassOnion_View_Helper_Favicon
    extends Zend_View_Helper_Abstract
{
    /**
     * Appends a theme's favicon to the head link placeholder 
     *
     * @param string $href
     * @param string $placement
     * @return Zend_View
     */
    public function favicon($href, $placement = Zend_View_Helper_Placeholder_Container_Abstract::APPEND)
    {
        $url = preg_match('/^(ht|f)tp(s)*:\/\/|^\//', $href)
            ? $href : $this->view->themeBaseUrl($href);
        $this->view->headLink(array('rel' => 'icon', 'href' => $url));
        return $this->view;
    }
}
