<?php

/**
 * @see Zend_Application_Resource_ResourceAbstract
 */
require_once 'Zend/Application/Resource/ResourceAbstract.php';

class GlassOnion_Application_Resource_Stylesheet extends Zend_Application_Resource_ResourceAbstract
{
	/**
	 * Sets the theme
	 *
	 * @param  string $theme
	 * @return GlassOnion_Application_Resource_Stylesheet
	 */
	public function setTheme($theme)
	{
		GlassOnion_View_Helper_Stylesheet::setTheme($theme);
		return $this;
	}

	/**
	 * Sets the base url
	 *
	 * @param  string $baseUrl
	 * @return GlassOnion_Application_Resource_Stylesheet
	 */
	public function setBaseUrl($baseUrl)
	{
		GlassOnion_View_Helper_Stylesheet::setBaseUrl($baseUrl);
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
