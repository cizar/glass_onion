<?php

/**
 * @see Zend_Application_Resource_ResourceAbstract
 */
require_once 'Zend/Application/Resource/ResourceAbstract.php';

class GlassOnion_Application_Resource_Stylesheet extends Zend_Application_Resource_ResourceAbstract
{
	/**
	 * Defined by Zend_Application_Resource_Resource
	 *
	 * @return void
	 */
	public function init()
	{
		$stylesheet = $this->getBootstrap()
			->bootstrap('view')->getResource('view')->stylesheet();

		foreach ($this->getOptions() as $key => $params)
		{
			$filter = new GlassOnion_Filter_UppercaseFirst();

			$method = 'set' . $filter->filter($key);

			call_user_func(array($stylesheet, $method), $params);
		}
	}
}
