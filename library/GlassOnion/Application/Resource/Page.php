<?php

class GlassOnion_Application_Resource_Page extends  Zend_Application_Resource_ResourceAbstract
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
    protected $_title_separator = ' :: ';

    /**
     * @var string
     */
    protected $_content_type = null;

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
		return $this->_title_separator;
    }

    /**
     * Sets the title separator
     *
	 * @param  string $title_separator
     * @return GlassOnion_Application_Resource_Page
     */
    public function setTitleSeparator($title_separator)
    {
		$this->_title_separator = $title_separator;
		return $this;
    }

    /**
     * Returns the page content type
     *
     * @return string
     */
    public function getContentType()
    {
		return $this->_content_type;
    }

    /**
     * Sets the page content type
     *
	 * @param  string $content_type
     * @return GlassOnion_Application_Resource_Page
     */
    public function setContentType($content_type)
    {
		$this->_content_type = $content_type;
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
			->bootstrap('view')->getResource('view');
			
		if ($this->_doctype)
		{
			$view->doctype($this->_doctype);
		}
		
		if ($this->_title)
		{
			$view->headTitle()
				->setSeparator($this->_title_separator)
				->append($this->_title);
		}
		
		if ($this->_content_type)
		{
			$view->headMeta()
				->appendHttpEquiv('Content-Type', $this->_content_type);
		}
    }
}
