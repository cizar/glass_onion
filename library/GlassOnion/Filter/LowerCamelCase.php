<?php

/**
 * @see Zend_Filter_Interface
 */
require_once 'Zend/Filter/Interface.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_Filter
 */
class GlassOnion_Filter_LowerCamelCase implements Zend_Filter_Interface
{
	/**
	 * Defined by Zend_Filter_Interface
	 *
	 * Converts a underscored string into lowerCamelCase
	 *
	 * @param string $value
	 * @return string
	 */
	public function filter($value)
	{
		return preg_replace('/_(.?)/e', 'strtoupper("$1")', $value);
	}
}
