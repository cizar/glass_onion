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
class GlassOnion_View_Helper_SwitchTheme
    extends Zend_View_Helper_Abstract
{
    /**
     * Changes the active theme
     *
     * @param string $filename
     * @return void
     */
    public function switchTheme($theme)
    {
        $this->view->getHelper('ThemeBaseUrl')->setTheme($theme);
    }
}
