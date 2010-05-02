<?php

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

class GlassOnion_View_Helper_Price extends Zend_View_Helper_Abstract
{
	private $_currency = '$ ';
	
    public function price($value = null, $currency = null)
    {
		if (null === $value)
		{
			return $this;
		}
		
		if (null === $currency)
		{
			$currency = $this->_currency;
		}

		return $currency . $this->view->number($value, 2);
    }

	public function setCurrency($currency)
	{
		$this->_currency = $currency;
		return $this;
	}
}
