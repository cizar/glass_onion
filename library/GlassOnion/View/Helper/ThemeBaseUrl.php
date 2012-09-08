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
class GlassOnion_View_Helper_ThemeBaseUrl
    extends Zend_View_Helper_Abstract
{
    /**
     * @const string
     */
    const DEFAULT_THEME = 'default';

    /**
     * @const string
     */
    const DEFAULT_BASE_URL = '/themes';

    /**
     * @var string
     */
    protected $_theme;
    
    /**
     * @var string
     */
    protected $_baseUrl;

    /**
     * Defined by Zend_Application_Resource_Resource
     *
     * @param string $theme
     * @return string
     */
    public function themeBaseUrl($file = null)
    {
        return $this->view->baseUrl(sprintf('%s/%s/%s',
            $this->getBaseUrl(),
            $this->getTheme(),
            $file));
    }

    /**
     * Set the theme name
     *
     * @param string $theme
     * @return GlassOnion_View_Helper_ThemeBaseUrl Provides a fluent interface
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
            $this->_theme = self::DEFAULT_THEME;
        }
        return $this->_theme;
    }

    /**
     * Set the theme base URL
     *
     * @param string $baseUrl
     * @return GlassOnion_View_Helper_ThemeBaseUrl Provides a fluent interface
     */
    public function setBaseUrl($baseUrl)
    {
        $this->_baseUrl = rtrim($baseUrl, '/\\');
        return $this;
    }

    /**
     * Returns the theme base URL
     *
     * @return string
     */
    public function getBaseUrl()
    {
        if (null === $this->_baseUrl) {
            $this->_baseUrl = self::DEFAULT_BASE_URL;
        }
        return $this->_baseUrl;
    }
}
