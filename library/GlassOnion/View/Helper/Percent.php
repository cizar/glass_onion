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
class GlassOnion_View_Helper_Percent extends Zend_View_Helper_Abstract
{
    private $_decimals = 0;

    private $_symbol = '%';

    private $_value = null;

    public function percent($data = null, $decimals = null)
    {
        $this->_value = $this->_format($data, $decimals);
        return $this;
    }

    public function setDecimals($decimals)
    {
        $this->_decimals = $decimals;
        return $this;
    }

    public function setSymbol($symbol)
    {
        $this->_symbol = $symbol;
        return $this;
    }

    /**
     * Returns the formated string.
     *
     * @return string
     */
    private function _format($data = null, $decimals = null)
    {
        if (null === $data) {
            return '';
        }

        $percent = $this->_getPercent($data);

        if (null === $decimals)
        {
            $decimals = $this->_decimals;
        }

        return $this->view->number($percent, $decimals) . $this->_symbol;
    }

    private function _getPercent($data)
    {
        if (is_array($data)) {
            list($value, $max) = $data;

            if ($max == 0) {
                return 0;
            }

            return $value / $max * 100;
        }
        else if (!is_numeric($data)) {
            throw new Zend_View_Exception('Data must be a numeric or array of [value, max].');
        }

        return (float) $data * 100;
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
