<?php

/**
 * @see Zend_Application_Resource_ResourceAbstract
 */
require_once 'Zend/Application/Resource/ResourceAbstract.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_Application
 */
class GlassOnion_Application_Resource_Headmeta
    extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * Defined by Zend_Application_Resource_Resource
     *
     * @return Zend_View_Helper_HeadMeta
     */
    public function init()
    {
        $bootstrap = $this->getBootstrap();

        if (!$bootstrap->hasResource('view')) {
            require_once 'Zend/Application/Resource/Exception.php';
            throw new Zend_Application_Resource_Exception('No view defined');
        }
        
        $headMeta = $bootstrap->bootstrap('view')
            ->getResource('view')->headMeta();

        foreach ($this->getOptions() as $key => $value) {
            $headMeta->appendHttpEquiv($key, $value);
        }
        
        return $headMeta;
    }
}
