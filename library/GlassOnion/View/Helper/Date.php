<?php

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * @see Zend_Date
 */
require_once 'Zend/Date.php';
  
class GlassOnion_View_Helper_Date extends Zend_View_Helper_Abstract
{
	private $_format = '';
	
	private $_value = null;
	
	public function date($date, $format = null)
	{
		$this->_value = $this->_format($date, $format);
		return $this;
	}
	
	/**
	 * Returns the formated string.
	 *
	 * @return string
	 */
	private function _format($date, $format)
	{
		$obj = new Zend_Date($date);
		return $obj->toString($format);
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
