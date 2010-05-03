<?php

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

class GlassOnion_View_Helper_Ellipsis  extends Zend_View_Helper_Abstract
{  
	public function ellipsis($string, $max = 50, $rep = '...')
	{
		$leave = $max - strlen($rep);
		return substr_replace($string, $rep, $leave);
    }
}
