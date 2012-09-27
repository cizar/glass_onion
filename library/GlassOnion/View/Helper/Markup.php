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
class GlassOnion_View_Helper_Markup extends Zend_View_Helper_Abstract
{
    /**
     * @const string
     */
    const DEFAULT_PARSER = 'Bbcode';

    /**
     * @var string
     */
    private $_value;

    /**
     * @var string
     */
    private $_parser;

    /**
     * Renders a text usign Zend_Markup
     *
     * @return GlassOnion_View_Helper_Markup Provides a fluent interface
     */
    public function markup($value, $parser = null)
    {
        if (null !== $parser) {
            $this->setParser($parser);
        }
        return $this->setValue($value);
    }
    
    /**
     * @return string
     */
    public function getValue()
    {
        if (null === $this->_value) {
            return ' ';
        }
        return $this->_value;
    }

    /**
     * @return GlassOnion_View_Helper_Markup Provides a fluent interface
     */
    public function setValue($value)
    {
        $this->_value = $value;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getParser()
    {
        if (null === $this->_parser) {
            $this->_parser = self::DEFAULT_PARSER;
        }
        return $this->_parser;
    }

    /**
     * @return GlassOnion_View_Helper_Markup Provides a fluent interface
     */
    public function setParser($parser)
    {
        $this->_parser = $parser;
        return $this;
    }

    /**
     * Cast to string
     *
     * @return string
     */
    public function __toString()
    {
        try
        {
            $markup = Zend_Markup::factory($this->getParser(), 'Html');
            return $markup->render($this->getValue());
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
}
