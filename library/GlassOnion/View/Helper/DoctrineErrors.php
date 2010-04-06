<?php

/**
 * @see Zend_View_Helper_FormElement
 */
require_once 'Zend/View/Helper/Abstract.php';

class GlassOnion_View_Helper_DoctrineErrors extends Zend_View_Helper_Abstract
{
	private $_translator = null;
	
	private $_html = null;
	
	private $_error_prefix = 'doctrine-error-';

	public function doctrineErrors(array $errors, array $attribs = null, $escape = true)
	{
		if (empty($attribs['class']))
		{
            $attribs['class'] = 'errors';
        }

		return $this->view->htmlList($this->_translateErrors($errors), false, $attribs, $escape);
	}
	
	public function setPrefix($prefix)
	{
		$this->_error_prefix = $prefix;

		return $this;
	}

	/**
     * Sets a translation Adapter for translation
     *
     * @param  Zend_Translate|Zend_Translate_Adapter $translate Instance of Zend_Translate
     * @throws Zend_View_Exception When no or a false instance was set
     * @return Zend_View_Helper_Translate
     */
    public function setTranslator($translate)
    {
        if ($translate instanceof Zend_Translate_Adapter) {
            $this->_translator = $translate;
        } else if ($translate instanceof Zend_Translate) {
            $this->_translator = $translate->getAdapter();
        } else {
            require_once 'Zend/View/Exception.php';
            $e = new Zend_View_Exception('You must set an instance of Zend_Translate or Zend_Translate_Adapter');
            $e->setView($this->view);
            throw $e;
        }

        return $this;
    }

    /**
     * Retrieve translation object
     *
     * @return Zend_Translate_Adapter|null
     */
    public function getTranslator()
    {
        if ($this->_translator === null) {
            require_once 'Zend/Registry.php';
            if (Zend_Registry::isRegistered('Zend_Translate')) {
                $this->setTranslator(Zend_Registry::get('Zend_Translate'));
            }
        }

        return $this->_translator;
    }

	private function _translateErrors($errors)
	{
		$translator = $this->getTranslator();
		
		$translated = array();
		
		foreach ($errors as $error)
		{
			$error_code = $this->_error_prefix . $error;
			
			$translated[] = $translator ? $translator->translate($error_code) : $error_code;
		}
		
		return $translated;
	}
}