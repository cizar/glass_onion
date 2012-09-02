<?php

/**
 * @see Zend_Application_Resource_ResourceAbstract
 */
require_once 'Zend/Application/Resource/ResourceAbstract.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_Application
 */
class GlassOnion_Application_Resource_Page
    extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * @var string
     */
    protected $_title = null;

    /**
     * @var string
     */
    protected $_titleSeparator = ' - ';

    /**
     * Returns the page title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * Sets the page title
     *
     * @param  string $doctype
     * @return GlassOnion_Application_Resource_Page
     */
    public function setTitle($title)
    {
        $this->_title = $title;
        return $this;
    }

    /**
     * Returns the title separator
     *
     * @return string
     */
    public function getTitleSeparator()
    {
        return $this->_titleSeparator;
    }

    /**
     * Sets the title separator
     *
     * @param  string $titleSeparator
     * @return GlassOnion_Application_Resource_Page
     */
    public function setTitleSeparator($titleSeparator)
    {
        $this->_titleSeparator = $titleSeparator;
        return $this;
    }

    /**
     * Defined by Zend_Application_Resource_Resource
     *
     * @return void
     */
    public function init()
    {
        $bootstrap = $this->getBootstrap();

        if (!$bootstrap->hasResource('view')) {
            require_once 'Zend/Application/Resource/Exception.php';
            throw new Zend_Application_Resource_Exception('No view defined');
        }

        $view = $bootstrap->bootstrap('view')->getResource('view');

        if (null === $this->_title) {
            require_once 'Zend/Application/Resource/Exception.php';
            throw new Zend_Application_Resource_Exception('The page title has not been defined');
        }

        $view->headTitle()
            ->setSeparator($this->_titleSeparator)
            ->append($this->_title);
    }
}
