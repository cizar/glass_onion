<?php

class GlassOnion_Application_Resource_Paginator extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * Set the default scrolling style for the Paginator
     *
     * @param  string $scrollingStyle
     * @return Core_Application_Resource_Paginator
     */
    public function setDefaultScrollingStyle($scrollingStyle)
    {
        Zend_Paginator::setDefaultScrollingStyle($scrollingStyle);
        return $this;
    }

    /**
     * Set the default view partial for the Paginator
     *
     * @param  string|array $viewPartial
     * @return Core_Application_Resource_Paginator
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
