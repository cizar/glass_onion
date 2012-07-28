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
     * @var string
     */
	private $_format = '';

    /**
     * @var string
     */
	private $_value = null;

    /**
     * Returns a formated date
     *
     * @return GlassOnion_View_Helper_Date Provides a fluent interface
     */
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
