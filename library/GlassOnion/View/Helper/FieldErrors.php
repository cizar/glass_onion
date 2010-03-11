<?php

/**
 * @see Zend_View_Helper_FormElement
 */
require_once 'Zend/View/Helper/FormElement.php';

class GlassOnion_View_Helper_FieldErrors extends Zend_View_Helper_FormElement
{
	private static $_path = null;
	
	private static $_cache = array();
	
	private static $_default_storage = null;
	
	public static function setMessagesPath($path)
	{
		if (!is_dir($path))
		{
			throw new Exception("The file directory '{$path}' does not exist");
		}

		self::$_path = $path;
	}

	public static function setDefaultStorage($name)
	{
		self::$_default_storage = self::_getStorage($name);
	}

	/**
	 * Returns a form label with the record error stack (Only for Doctrine)
	 *
	 * @param string $field
	 * @param Doctrine_Record $record
	 * @return Zend_View_Helper_FormLabel
	 */
	public function fieldErrors($field, Doctrine_Record $record)
	{
		$errorStack = $record->getErrorStack();

		if (!$errorStack->contains($field))
		{
			return '';
		}

		$list = array();
		
		foreach ($errorStack->get($field) as $code)
		{
			$list[] = $this->_getErrorMessage($record, $field, $code);
		}

		return $this->view->htmlList($list);
	}

	private function _getErrorMessage($record, $field, $code)
	{
		$message = $code;

		if (self::$_default_storage && isset(self::$_default_storage->$code))
		{
			$message = self::$_default_storage->$code;
		}

		$storage = self::_getStorage(get_class($record))->get($field);

		if ($storage instanceof Zend_Config && isset($storage->$code))
		{
			$message = $storage->get($code);
		}

		return $message;
	}

	private static function _getStorage($name)
	{
		if (!array_key_exists($name, self::$_cache))
		{
			$filename = self::$_path . '/' . $name . '.ini';

			self::$_cache[$name] = new Zend_Config_Ini($filename);
		}

		return self::$_cache[$name];
	}
}
