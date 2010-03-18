<?php

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * @see Zend_Date
 */
require_once 'Zend/Date.php';
  
class GlassOnion_View_Helper_FormatDate
{  
	public function formatDate($date, $format = null)
	{
		$date = new Zend_Date($date);
		
		return $date->toString($format);
	}
}
