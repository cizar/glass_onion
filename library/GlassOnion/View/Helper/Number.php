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
     * @var integer
     */
    private $_decimals = 2;

    /**
     * @var string
     */
    private $_decPoint = '.';

    /**
     * @var string
     */
    private $_thousandsSep = ',';

    /**
     * @var numeric
     */
    private $_value = null;

    public function number($value = null, $decimals = null, $decPoint = null, $thousandsSep = null)
    {
        $this->_value = $this->_format($value, $decimals, $decPoint, $thousandsSep);
        return $this;
    }

    public function setDecimals($decimals)
    {
        $this->_decimals = $decimals;
        return $this;
    }

    public function setDecPoint($decPoint)
    {
        $this->_decPoint = $decPoint;
        return $this;
    }

    public function setThousandsSep($thousandsSep)
    {
        $this->_thousandsSep = $thousandsSep;
        return $this;
    }

    /**
     * Returns the formated string.
     *
     * @return string
     */
    private function _format($value, $decimals, $decPoint, $thousandsSep)
    {
        if (null === $value) {
            return '';
        }

        if (!is_numeric($value)) {
            throw new Zend_View_Exception('The value must be numeric.');
        }

        return number_format($value,
            is_null($decimals) ? $this->_decimals : $decimals,
            is_null($decPoint) ? $this->_decPoint : $decPoint,
            is_null($thousandsSep) ? $this->_thousandsSep : $thousandsSep
        );
    }
    
    /**
     * Cast to string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->_value;
    }
}
