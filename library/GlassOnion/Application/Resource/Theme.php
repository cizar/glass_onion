<?php

/**
 * @see Zend_Application_Resource_ResourceAbstract
 */
require_once 'Zend/Application/Resource/ResourceAbstract.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_Application
 */
class GlassOnion_Application_Resource_Theme
    extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * @var string
     */
    protected $_theme;

    /**
     * Defined by Zend_Application_Resource_Resource
     *
     * @return string
     */
    public function init()
    {
        $theme = $this->getTheme();

        $bootstrap = $this->getBootstrap();
        if ($bootstrap->hasResource('view')) {
            $bootstrap->bootstrap('view')->getResource('view')
                ->getHelper('themeBaseUrl')->setTheme($theme);
        }

        return $theme;
    }

    /**
     * Returns the defined theme name
     *
     * @return string
     */
    public function getTheme()
    {
        if (null === $this->_theme) {
            $options = $this->getOptions();
            $this->_theme = isset($options['name']) ? $options['name'] : 'default';
        }
        return $this->_theme;
    }
}
