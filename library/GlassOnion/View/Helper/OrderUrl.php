<?php

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

class GlassOnion_View_Helper_OrderUrl extends Zend_View_Helper_Abstract
{
	public function orderUrl($field, $order, $default_order_asc = 'asc')
	{
		if ($order['field'] == $field)
		{
			$order_asc = $order['asc'] == 'asc' ? 'desc' : 'asc';
		}
		else
		{
			$order_asc = $default_order_asc;
		}

		$query_string = empty($_SERVER['QUERY_STRING']) ? '' : '?' . $_SERVER['QUERY_STRING'];

		return $this->view->url(array('order' => $field, 'order_asc' => $order_asc)) . $query_string;
	}
}
