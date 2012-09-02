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
     * @return void
     */
    public function init()
    {
        $bootstrap = $this->getBootstrap();

        if (!$bootstrap->hasResource('view')) {
            require_once 'Zend/Application/Resource/Exception.php';
            throw new Zend_Application_Resource_Exception('No view defined');
        }
        
        $view = $bootstrap->getResource('view');

        foreach ($this->getOptions() as $key => $value) {
            $view->headMeta()
                ->appendHttpEquiv($key, $value);
        }
    }
}
