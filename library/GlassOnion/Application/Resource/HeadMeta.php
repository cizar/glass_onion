<?php

/**
 * @see Zend_Application_Resource_ResourceAbstract
 */
require_once 'Zend/Application/Resource/ResourceAbstract.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_Application
 */
class GlassOnion_Application_Resource_HeadMeta
    extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * Defined by Zend_Application_Resource_Resource
     *
     * @return void
     */
    public function init()
    {
        $view = $this->getBootstrap()
            ->bootstrap('view')->getResource('view');

        foreach ($this->getOptions() as $key => $value)
        {
            $view->headMeta()
                ->appendHttpEquiv($key, $value);
        }
    }
}
