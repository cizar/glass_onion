<?php

require_once 'Zend/View/Helper/FormElement.php';

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
			$html .= '<li>' . get_class($record) . '<ul>';

			foreach ($record->getErrorStack() as $field => $errors)
			{
				$html .= '<li>' . $field . '<ul>';

				foreach ($errors as $error)
				{
					$html .= '<li>' . $error . '</li>';
				}

				$html .= '</ul></li>';
			}

			$html .= '</ul></li>';

		}
		$html .= '</ul>';

		return $html;
	}
}
