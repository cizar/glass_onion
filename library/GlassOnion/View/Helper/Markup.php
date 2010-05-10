<?php

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

class GlassOnion_View_Helper_Markup extends Zend_View_Helper_Abstract
{
	private $_parser = 'Bbcode';

	private $_value = null;

	public function markup($text, $parser = 'Bbcode')
	{
		$this->_value = $this->_format($text, $parser);
		return $this;
	}

	public function setParser($parser)
	{
		$this->_parser = $parser;
		return $this;
	}

	/**
	 * Returns the formated string.
	 *
	 * @return string
	 */
	private function _format($text, $parser)
	{
		if (is_null($text) || empty($text))
		{
			return '';
		}

		$markup = Zend_Markup::factory($parser, 'Html');

		return $markup->render($text);
	}

	/**
	 * Cast to string
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->_value;
	}
}
