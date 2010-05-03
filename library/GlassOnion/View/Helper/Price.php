<?php

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

class GlassOnion_View_Helper_Price extends Zend_View_Helper_Abstract
{
	private $_currency = '$ ';
	
	private $_value = null;
	
    public function price($value = null, $currency = null)
    {
		$this->value = $this->_format($value, $currency);
		return $this;
    }

	public function setCurrency($currency)
	{
		$this->_currency = $currency;
		return $this;
	}

	/**
	 * Returns the formated string.
	 *
	 * @return string
	 */
	private function _format($value, $currency)
	{
		if (null === $value)
		{
			return null;
		}
		
		if (null == $currency)
		{
			$currency = $this->currency;
		}
		
		return $currency . $this->view->number($value, 2);
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
