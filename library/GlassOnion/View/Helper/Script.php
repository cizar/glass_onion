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
class GlassOnion_View_Helper_Script
    extends Zend_View_Helper_Abstract
{
    /**
     * Defined by Zend_Application_Resource_Resource
     *
     * @param string $href
     * @return Zend_View
     */
    public function script($href, $type = 'text/javascript')
    {
        $url = preg_match('/^(ht|f)tp(s)*:\/\/|^\//', $href)
            ? $href : $this->view->baseUrl($href);
        $this->view->headScript()->appendFile($url, $type);
        return $this->view;
    }
}
