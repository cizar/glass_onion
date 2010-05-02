<?php

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

class GlassOnion_View_Helper_Number extends Zend_View_Helper_Abstract
{
	private $_decimals = 2;
	
	private $_decPoint = '.';
	
	private $_thousandsSep = ',';
	
    public function number($value = null, $decimals = null, $decPoint = null, $thousandsSep = null)
    {
		if (null === $value)
		{
			return $this;
		}
		
        return number_format($value,
			is_null($decimals) ? $this->_decimals : $decimals,
			is_null($decPoint) ? $this->_decPoint : $decPoint,
			is_null($thousandsSep) ? $this->_thousandsSep : $thousandsSep
		);
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
}
