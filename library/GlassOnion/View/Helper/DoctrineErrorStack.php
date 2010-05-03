<?php

/**
 * @see Zend_View_Helper_FormElement
 */
require_once 'Zend/View/Helper/Abstract.php';

class Zend_View_Helper_DoctrineErrorStack extends Zend_View_Helper_Abstract
{
	public function doctrineErrorStack(Doctrine_Validator_ErrorStack $errorStack)
	{
		$html = '<ul>';

		foreach ($errorStack as $field => $errors)
		{
			$html .= '<li>' . $field . '<ul>' . (string) $this->view->doctrineErrors($errors) . '</ul></li>';
		}

		$html .= '</ul>';

		return $html;
	}
}
