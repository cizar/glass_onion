<?php

/**
 * @see Zend_View_Helper_HtmlElement
 */
require_once 'Zend/View/Helper/HtmlElement.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_View
 * @subpackage Helper
 */
class GlassOnion_View_Helper_HtmlAnchor
    extends Zend_View_Helper_HtmlElement
{
    /**
     * The anchor label
     *
     * @var string
     */
    protected $_label;
    
    /**
     * Attributes for HTML anchor tag
     *
     * @var array
     */
    protected $_attribs;
    
    /**
     * Whether or not auto-translation is enabled
     *
     * @var boolean
     */
    protected $_translate = false;

    /**
     * Translation object
     *
     * @var Zend_Translate_Adapter
     */
    protected $_translator;
    
    /**
     * Defined by Zend_Application_Resource_Resource
     *
     * @param string $href
     * @param string $label
     * @param array $attribs
     * @return GlassOnion_View_Helper_HtmlAnchor Provides a fluent interface
     */
    public function htmlAnchor($href, $label = null, $attribs = array())
    {
        $this->_label = is_null($label) ? $href : $label;
        $this->_attribs = array_merge($attribs, array('href' => $href));
        return $this;
    }
    
    /**
     * Returns the translated anchor label
     *
     * @return string
     */
    public function getLabel()
    {
        if ($this->_translate && $t = $this->getTranslator()) {
            return $t->translate($this->_label);
        }
        return $this->_label;
    }
    
    /**
     * Sets a translation Adapter for translation
     *
     * @param  Zend_Translate|Zend_Translate_Adapter $translate
     * @return GlassOnion_View_Helper_HtmlAnchor Provides a fluent interface
     */
    public function setTranslator($translate)
    {
        if ($translate instanceof Zend_Translate_Adapter) {
            $this->_translator = $translate;
        } elseif ($translate instanceof Zend_Translate) {
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
     * If none is currently registered, attempts to pull it from the registry
     * using the key 'Zend_Translate'.
     *
     * @return Zend_Translate_Adapter|null
     */
    public function getTranslator()
    {
        if (null === $this->_translator) {
            require_once 'Zend/Registry.php';
            if (Zend_Registry::isRegistered('Zend_Translate')) {
                $this->setTranslator(Zend_Registry::get('Zend_Translate'));
            }
        }
        return $this->_translator;
    }

    /**
     * Enables translation
     *
     * @return GlassOnion_View_Helper_HtmlAnchor Provides a fluent interface
     */
    public function enableTranslation()
    {
        $this->_translate = true;
        return $this;
    }

    /**
     * Disables translation
     *
     * @return GlassOnion_View_Helper_HtmlAnchor Provides a fluent interface
     */
    public function disableTranslation()
    {
        $this->_translate = false;
        return $this;
    }

    /**
     * Return the complete HTML anchor tag
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf('<a%s>%s</a>',
            $this->_htmlAttribs($this->_attribs),
            $this->view->escape($this->getLabel()));
    }
}
