<?php

/**
 * @see Zend_Application_Resource_ResourceAbstract
 */
require_once 'Zend/Application/Resource/ResourceAbstract.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_Application
 */
class GlassOnion_Application_Resource_Headtitle
    extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * Defined by Zend_Application_Resource_Resource
     *
     * @return Zend_View_Helper_HeadTitle
     */
    public function init()
    {
        $bootstrap = $this->getBootstrap();

        if (!$bootstrap->hasResource('view')) {
            require_once 'Zend/Application/Resource/Exception.php';
            throw new Zend_Application_Resource_Exception('No view defined');
        }

        $headTitle = $bootstrap->bootstrap('view')
            ->getResource('view')->headTitle();

        $options = $this->getOptions();

        if (!isset($options['title'])) {
            require_once 'Zend/Application/Resource/Exception.php';
            throw new Zend_Application_Resource_Exception(
                'The page title has not been defined');
        }

        $headTitle->append($options['title']);

        if (isset($options['separator'])) {
            $headTitle->setSeparator($options['separator']);
        }

        if (isset($options['prefix'])) {
            $headTitle->setPrefix($options['prefix']);
        }

        if (isset($options['default_attach_order'])) {
            $headTitle->setDefaultAttachOrder($options['default_attach_order']);
        }
        
        return $headTitle;
    }
}
