<?php

/**
 * @see Zend_View_Helper_FormElement
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_View
 * @subpackage Helper
 */
class GlassOnion_View_Helper_DoctrineErrors extends Zend_View_Helper_Abstract
{
    /**
     * @var Zend_Translate
     */
    private $_translator = null;

    /**
     * @var string
     */
    private $_errorPrefix = 'doctrine-error-';

    /**
     * Generate a 'list' of Doctrine errors
     *
     * @param array $errors
     * @param array $attribs
     * @param boolean $escape
     * @return string The list XHTML
     */
    public function doctrineErrors(array $errors, array $attribs = null, $escape = true)
    {
        if (empty($attribs['class'])) {
            $attribs['class'] = 'errors';
        }

        return $this->view->htmlList($this->_translateErrors($errors), false, $attribs, $escape);
    }
    
    /**
     * Sets the error prefix
     *
     * @param string $prefix
     * @return Zend_View_Helper_Translate Provides a fluent interface
     */
    public function setPrefix($prefix)
    {
        $this->_errorPrefix = $prefix;

        return $this;
    }

    /**
     * Sets a translation Adapter for translation
     *
     * @param Zend_Translate|Zend_Translate_Adapter $translate Instance of Zend_Translate
     * @throws Zend_View_Exception When no or a false instance was set
     * @return Zend_View_Helper_Translate Provides a fluent interface
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
     * Retrieve the translation object
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

    /**
     * Translate a collection of errors
     *
     * @return array
     */
    private function _translateErrors($errors)
    {
        $translator = $this->getTranslator();

        $translated = array();

        foreach ($errors as $error) {
            $errorCode = $this->_errorPrefix . $error;
            $translated[] = $translator ? $translator->translate($errorCode) : $errorCode;
        }

        return $translated;
    }
}
