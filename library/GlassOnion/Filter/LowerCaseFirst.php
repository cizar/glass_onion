<?php

/**
 * @see Zend_Filter_Interface
 */
require_once 'Zend/Filter/Interface.php';

class GlassOnion_Filter_LowercaseFirst implements Zend_Filter_Interface
{
	/**
	 * Defined by Zend_Filter_Interface
	 *
	 * Returns the string $value, converting the first character to lowercase
	 *
	 * @param string $value
	 * @return string
	 */
	public function filter($value)
	{
		$value[0] = strtolower((string) $value[0]);
		return $value;
	}
}
