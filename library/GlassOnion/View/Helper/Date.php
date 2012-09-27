<?php

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * @see Zend_Date
 */
require_once 'Zend/Date.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_View
 * @subpackage Helper
 */
class GlassOnion_View_Helper_Date extends Zend_View_Helper_Abstract
{
    /**
     * @const string
     */
    const DEFAULT_FORMAT = 'Y-m-d';

    /**
     * @var string
     */
    private $_format;

    /**
     * @var string
     */
    private $_value;

    /**
     * Returns a formated date
     *
     * @param string $value
     * @param string $format
     * @return GlassOnion_View_Helper_Date Provides a fluent interface
     */
    public function date($value)//, $format = null)
    {
        return $this->setValue($value);//->setFormat($format);
    }

    /**
     * Returns the date value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->_value;
    }

    /**
     * Returns a formated date
     *
     * @param string $value
     * @return GlassOnion_View_Helper_Date Provides a fluent interface
     */
    public function setValue($value)
    {
        $this->_value = $value;
        return $this;
    }

    /**
     * Returns the date format
     *
     * @return string
     */
    public function getFormat()
    {
        if (null === $this->_format) {
            $this->_format = self::DEFAULT_FORMAT;
        }
        return $this->_format;
    }

    /**
     * Returns a formated date
     *
     * @param string $format
     * @return GlassOnion_View_Helper_Date Provides a fluent interface
     */
    public function setFormat($format)
    {
        $this->_format = $format;
        return $this;
    }

    /**
     * Cast to string
     *
     * @return string
     */
    public function __toString()
    {
        $obj = new Zend_Date($this->getValue(), 'Y-m-d');
        return $obj->toString($this->getformat());
    }
}
