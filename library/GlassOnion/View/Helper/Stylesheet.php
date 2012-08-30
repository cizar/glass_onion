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
    public function stylesheet($filename)
    {
        return $this->view->headLink()->appendStylesheet(
            $this->view->themeBaseUrl($filename));
    }
}
