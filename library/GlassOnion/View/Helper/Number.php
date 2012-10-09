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
class GlassOnion_View_Helper_Number extends Zend_View_Helper_Abstract
{
    /**
     * @const integer
     */
    const DEFAULT_DECIMALS = 2;

    /**
     * @const string
     */
    const DEFAULT_DEC_POINT = '.';

    /**
     * @const string
     */
    const DEFAULT_THOUSANDS_SEP = ',';

    /**
     * @var numeric
     */
    private $_value;

    /**
     * @var integer
     */
    private $_decimals;

    /**
     * @var string
     */
    private $_decPoint;

    /**
     * @var string
     */
    private $_thousandsSep;

    /**
     * Helper entry point
     *
     * @return GlassOnion_View_Helper_Number Provides a fluent interface
     */
    public function number($value = null, $decimals = null, $decPoint = null, $thousandsSep = null)
    {
        if (null !== $value) {
            $this->setValue($value);
        }
        if (null !== $decimals) {
            $this->setDecimals($decimals);
        }
        if (null !== $decPoint) {
            $this->setDecPoint($decPoint);
        }
        if (null !== $thousandsSep) {
            $this->setThousandsSep($thousandsSep);
        }
        return $this;
    }

    /**
     * Returns the number to being formatted
     *
     * @return numeric
     */
    public function getValue()
    {
        return $this->_value;
    }

    /**
     * Sets the number to being formatted
     *
     * @return GlassOnion_View_Helper_Number Provides a fluent interface
     */
    public function setValue($value)
    {
        if (!is_numeric($value)) {
            throw new Zend_View_Exception('The value must be numeric.');
        }
        $this->_value = $value;
        return $this;
    }

    /**
     * Returns the number of decimal points
     *
     * @return integer
     */
    public function getDecimals()
    {
        if (null === $this->_decimals) {
            $this->_decimals = self::DEFAULT_DECIMALS;
        }
        return $this->_decimals;
    }

    /**
     * Sets the number of decimal points
     *
     * @return GlassOnion_View_Helper_Number Provides a fluent interface
     */
    public function setDecimals($decimals)
    {
        if (!is_integer($decimals) || $decimals < 0) {
            throw new Zend_View_Exception('The number of decimal points must be integer and positive.');
        }
        $this->_decimals = $decimals;
        return $this;
    }

    /**
     * Returns the separator for the decimal point
     *
     * @return string
     */
    public function getDecPoint()
    {
        if (null === $this->_decPoint) {
            $this->_decPoint = self::DEFAULT_DEC_POINT;
        }
        return $this->_decPoint;
    }

    /**
     * Sets the separator for the decimal point
     *
     * @return GlassOnion_View_Helper_Number Provides a fluent interface
     */
    public function setDecPoint($decPoint)
    {
        $this->_decPoint = $decPoint;
        return $this;
    }

    /**
     * Returns the thousands separator
     *
     * @return string
     */
    public function getThousandsSep()
    {
        if (null === $this->_thousandsSep) {
            $this->_thousandsSep = self::DEFAULT_THOUSANDS_SEP;
        }
        return $this->_thousandsSep;
    }

    /**
     * Sets the thousands separator
     *
     * @return GlassOnion_View_Helper_Number Provides a fluent interface
     */
    public function setThousandsSep($thousandsSep)
    {
        $this->_thousandsSep = $thousandsSep;
        return $this;
    }

    /**
     * Cast to string
     *
     * @return string
     */
    public function __toString()
    {
        return number_format($this->getValue(), $this->getDecimals(), $this->getDecPoint(), $this->getThousandsSep());
    }
}
