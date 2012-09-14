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
class GlassOnion_View_Helper_Title
    extends Zend_View_Helper_Abstract
{
    /**
     * Sets the page title and returns its value
     *
     * @param string $title
     * @return string
     */
    public function title($title)
    {
        $this->view->headTitle($title);
        return $title;
    }
}
