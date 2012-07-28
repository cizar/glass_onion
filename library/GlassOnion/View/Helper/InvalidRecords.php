<?php

/**
 * @see Zend_View_Helper_FormElement
 */
require_once 'Zend/View/Helper/FormElement.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_View
 * @subpackage Helper
 */
class Zend_View_Helper_InvalidRecords extends Zend_View_Helper_FormElement
{
	public function invalidRecords($invalidRecords)
	{
		if (!$invalidRecords)
		{
			return '';
		}

		$html = '<ul>';

		foreach ($invalidRecords as $record)
		{
			$html .= '<li>' . get_class($record);

			$html .= $this->view->doctrineErrorStack($record->getErrorStack());

			$html .= '</li>';

		}

		$html .= '</ul>';

		return $html;
	}
}
