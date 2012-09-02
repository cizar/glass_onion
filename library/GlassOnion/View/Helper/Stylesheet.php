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
     * @param string $filename
     * @return GlassOnion_View_Helper_Stylesheet
     */
    public function stylesheet($filename = null)
    {
        $this->view->headLink()->appendStylesheet(
            $this->view->themeBaseUrl($filename));
        return $this->view;
    }
}
