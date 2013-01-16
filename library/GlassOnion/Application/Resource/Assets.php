<?php

/**
 * @see Zend_Application_Resource_ResourceAbstract
 */
require_once 'Zend/Application/Resource/ResourceAbstract.php';

/**
 * @see GlassOnion_Assets_Container
 */
require_once 'GlassOnion/Assets/Container.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_Application
 */
class GlassOnion_Application_Resource_Assets
    extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * Defined by Zend_Application_Resource_Resource
     *
     * @return GlassOnion_Assets_Container
     */
    public function init()
    {
        $options = $this->getOptions();
        return GlassOnion_Assets_Container::fromYaml($options['config']);
    }
}
