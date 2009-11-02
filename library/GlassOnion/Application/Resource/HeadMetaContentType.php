<?php

class GlassOnion_Application_Resource_HeadMetaContentType extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * @var string
     */
    protected $_contentType = null;
	
    /**
     * Returns the content type
     *
     * @return string
     */
	public function getContentType()
	{
		return $this->_contentType;
	}

    /**
     * Sets the content type
     *
     * @param  string $contentType
     * @return GlassOnion_Application_Resource_HeadMetaContentType
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
		if ($this->_contentType)
		{
			$view = $this->getBootstrap()
				->bootstrap('view')->getResource('view');
			
			$view->headMeta()
				->appendHttpEquiv('Content-Type', $this->getContentType());
		}
    }
}
