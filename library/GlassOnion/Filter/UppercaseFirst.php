<?php

/**
 * @see Zend_Filter_Interface
 */
require_once 'Zend/Filter/Interface.php';

class GlassOnion_Filter_UppercaseFirst implements Zend_Filter_Interface
{
	/**
	 * Defined by Zend_Filter_Interface
	 *
	 * Returns the string $value, converting the first character to uppercase
	 *
	 * @param string $value
	 * @return string
	 */
	public function filter($value)
	{
		$value[0] = strtoupper((string) $value[0]);
		return $value;
	}
}
