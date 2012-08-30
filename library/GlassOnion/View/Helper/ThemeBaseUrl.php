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
class GlassOnion_View_Helper_ThemeBaseUrl extends Zend_View_Helper_Abstract
{
    /**
     * @var string
     */
    protected $_theme;

    /**
     * @var string
     */
    protected $_file;

    /**
     * Defined by Zend_Application_Resource_Resource
     *
     * @param string $theme
     * @return GlassOnion_View_Helper_ThemeBaseUrl
     */
    public function themeBaseUrl($file = null)
    {
        $this->_file = $file;
        return $this;
    }

    /**
     * Set the theme name
     *
     * @param string $theme
     * @return GlassOnion_View_Helper_ThemeBaseUrl
     */
    public function setTheme($theme)
    {
        $this->_theme = $theme;
        return $this;
    }

    /**
     * Returns the theme name
     *
     * @return string
     */
    public function getTheme()
    {
        if (null === $this->_theme) {
            $this->_theme = 'default';
        }
        return $this->_theme;
    }

    /**
     * Returns the theme's base URL, or file with base url prepended
     *
     * @return string
     */
    public function __toString()
    {
        return $this->view->baseUrl('/themes/' . $this->getTheme() . '/' . $this->_file);
    }
}
