<?php

/**
 * @see Zend_View_Helper_FormElement
 */
require_once 'Zend/View/Helper/FormElement.php';

class GlassOnion_View_Helper_FieldErrors extends Zend_View_Helper_FormElement
{
	/**
	 * Returns a form label with the record error stack (Only for Doctrine)
	 *
	 * @param string $field
	 * @param Doctrine_Record $record
	 * @param array $messages
	 * @return Zend_View_Helper_FormLabel
	 */
	public function fieldErrors($field, Doctrine_Record $record, $messages = array())
	{
		$errorStack = $record->getErrorStack();
		
		if (!$errorStack->contains($field))
		{
			return '';
		}

		$list = array();
		
		foreach ($errorStack->get($field) as $code)
		{
			$list[] = array_key_exists($code, $messages)
				? $messages[$code] : $code;
		}

		return $this->view->htmlList($list);
	}
}
