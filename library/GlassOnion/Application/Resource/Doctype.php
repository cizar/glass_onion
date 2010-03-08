<?php

/**
 * @see Zend_Application_Resource_ResourceAbstract
 */
require_once 'Zend/Application/Resource/ResourceAbstract.php';

class GlassOnion_Application_Resource_Doctype extends Zend_Application_Resource_ResourceAbstract
{
	/**
	 * @var string
	 */
	protected $_doctype = null;

	/**
	 * Returns the doctype
	 *
	 * @return string
	 */
	public function getDoctype()
	{
		return $this->_doctype;
	}

	/**
	 * Sets the doctype
	 *
	 * @param  string $doctype
	 * @return GlassOnion_Application_Resource_Doctype
	 */
	public function setDoctype($doctype)
	{
		$this->_doctype = $doctype;
		return $this;
	}

	/**
	 * Defined by Zend_Application_Resource_Resource
	 *
	 * @return void
	 */
	public function init()
    {
		if ($this->_doctype)
		{
			$view = $this->getBootstrap()
				->bootstrap('view')->getResource('view');

			$view->doctype($this->_doctype);
		}
    }
}
