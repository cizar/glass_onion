<?php

/**
 * @see Zend_Application_Resource_ResourceAbstract
 */
require_once 'Zend/Application/Resource/ResourceAbstract.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_Application
 */
class GlassOnion_Application_Resource_Fop
    extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * @var GlassOnion_Fop
     */
    protected $_fop = null;

    /**
     * Defined by Zend_Application_Resource_Resource
     *
     * @return GlassOnion_Fop
     */
    public function init()
    {
        return $this->getFop();
    }

    /**
     * getFop() - Returns a configured Fop object
     *
     * @return GlassOnion_Fop
     */
    public function getFop()
    {
        if (null === $this->_fop) {
            $this->_fop = GlassOnion_Fop::factory($this->getOptions());
        }

        return $this->_fop;
    }
}
