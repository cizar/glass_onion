<?php

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

class GlassOnion_View_Helper_Stylesheet extends Zend_View_Helper_Abstract
{
	private static $_theme = 'default';
	
	private static $_baseUrl = 'styles';
	
	public static function setTheme($theme)
	{
		self::$_theme = $theme;
	}
	
	public static function setBaseUrl($baseUrl)
	{
		self::$_baseUrl = rtrim($baseUrl, '/\\');
	}
	
	public function stylesheet($href, $media = 'screen', $conditionalStylesheet = null, $extras = null)
	{
		$url = preg_match('/^(ht|f)tp(s)*:\/\/|^\//', $href)
			? $href : $this->view->baseUrl(self::$_baseUrl . '/' . self::$_theme . '/' . $href);

		$this->view->headLink()->appendStylesheet($url, $media, $conditionalStylesheet, $extras);
		
		return $this->view;
	}
}
