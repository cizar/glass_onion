<?php

/**
 * @see Zend_Application_Resource_ResourceAbstract
 */
require_once 'Zend/Application/Resource/ResourceAbstract.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_Application
 */
class GlassOnion_Application_Resource_Page
    extends Zend_Application_Resource_ResourceAbstract
{
	/**
	 * @var string
	 */
	protected $_doctype = null;

	/**
	 * @var string
	 */
	protected $_title = null;

	/**
	 * @var string
	 */
	protected $_titleSeparator = ' :: ';

	/**
	 * @var string
	 */
	protected $_contentType = null;

	/**
	 * Returns the page doctype
	 *
	 * @return string
	*/
	public function getDoctype()
	{
		return $this->_doctype;
	}

	/**
	 * Sets the page doctype
	 *
	 * @param  string $doctype
	 * @return GlassOnion_Application_Resource_Page
	 */
	public function setDoctype($doctype)
	{
		$this->_doctype = $doctype;
		return $this;
	}

	/**
	 * Returns the page title
	 *
	 * @return string
	 */
	public function getTitle()
	{
		return $this->_title;
	}

	/**
	 * Sets the page title
	 *
	 * @param  string $doctype
	 * @return GlassOnion_Application_Resource_Page
	 */
	public function setTitle($title)
	{
		$this->_title = $title;
		return $this;
	}

	/**
	 * Returns the title separator
	 *
	 * @return string
	 */
	public function getTitleSeparator()
	{
		return $this->_titleSeparator;
	}

	/**
	 * Sets the title separator
	 *
	 * @param  string $titleSeparator
	 * @return GlassOnion_Application_Resource_Page
	 */
	public function setTitleSeparator($titleSeparator)
	{
		$this->_titleSeparator = $titleSeparator;
		return $this;
	}

	/**
	 * Returns the page content type
	 *
	 * @return string
	 */
	public function getContentType()
	{
		return $this->_contentType;
	}

	/**
	 * Sets the page content type
	 *
	 * @param  string $contentType
	 * @return GlassOnion_Application_Resource_Page
	 */
	public function setContentType($contentType)
	{
		$this->_contentType = $contentType;
		return $this;
	}

	/**
	 * Defined by Zend_Application_Resource_Resource
	 *
	 * @return void
	 */
	public function init()
	{
		$view = $this->getBootstrap()
			->bootstrap('view')
            ->getResource('view');

		if ($this->_doctype)
		{
			$view->doctype($this->_doctype);
		}

		if ($this->_title)
		{
			$view->headTitle()
				->setSeparator($this->_titleSeparator)
				->append($this->_title);
		}

		if ($this->_contentType)
		{
			$view->headMeta()
				->appendHttpEquiv('Content-Type', $this->_contentType);
		}
	}
}
