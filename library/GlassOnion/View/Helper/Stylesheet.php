<?php

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_View
 * @subpackage Helper
 */
class GlassOnion_View_Helper_Stylesheet extends Zend_View_Helper_Abstract
{
	private $_theme = 'default';
	
	private $_baseUrl = 'styles';
	
	public function stylesheet($href = null, $media = 'screen', $conditionalStylesheet = null, $extras = null)
	{
		if (null === $href)
		{
			return $this;
		}

		$url = preg_match('/^(ht|f)tp(s)*:\/\/|^\//', $href)
			? $href : $this->view->baseUrl($this->getThemeBaseUrl() . '/' . $href);

		$this->view->headLink()->appendStylesheet($url, $media, $conditionalStylesheet, $extras);
		
		return $this->view;
	}

	public function setTheme($theme)
	{
		$this->_theme = $theme;
		return $this;
	}
	
	public function setBaseUrl($baseUrl)
	{
		$this->_baseUrl = rtrim($baseUrl, '/\\');
		return $this;
	}
	
	public function getThemeBaseUrl()
	{
		return $this->_baseUrl . '/' . $this->_theme;
	}
}
