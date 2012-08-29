<?php

/**
 * @see Zend_Application_Resource_ResourceAbstract
 */
require_once 'Zend/Application/Resource/ResourceAbstract.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_Application
 */
class GlassOnion_Application_Resource_Paginator
    extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * Sets the default scrolling style for the Paginator
     *
     * @param  string $scrollingStyle
     * @return GlassOnion_Application_Resource_Paginator
     */
    public function setDefaultScrollingStyle($scrollingStyle)
    {
        Zend_Paginator::setDefaultScrollingStyle($scrollingStyle);
        return $this;
    }

    /**
     * Sets the default view partial for the Paginator
     *
     * @param  string|array $viewPartial
     * @return GlassOnion_Application_Resource_Paginator
     */
    public function setDefaultViewPartial($viewPartial)
    {
        Zend_View_Helper_PaginationControl::setDefaultViewPartial($viewPartial);
        return $this;
    }

    /**
     * Defined by Zend_Application_Resource_Resource
     *
     * @return void
     */
    public function init()
    {
    }
}
