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
class GlassOnion_View_Helper_Stylesheet
    extends Zend_View_Helper_Abstract
{
    /**
     * Defined by Zend_Application_Resource_Resource
     *
     * @param string $href
     * @param string $media
     * @param string $conditionalStylesheet
     * @param array $extras
     * @return Zend_View
     */
    public function stylesheet($href, $media = 'screen',
        $conditionalStylesheet = null, $extras = null)
    {
        $url = preg_match('/^(ht|f)tp(s)*:\/\/|^\//', $href)
            ? $href : $this->view->themeBaseUrl($href);
        $this->view->headLink()->appendStylesheet($url,
            $media, $conditionalStylesheet, $extras);
        return $this->view;
    }
}
