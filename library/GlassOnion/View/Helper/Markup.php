<?php

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_View
 * @subpackage Helper
 */
class GlassOnion_View_Helper_Markup extends Zend_View_Helper_Abstract
{
    /**
     * @var string
     */
    private $_value = null;

    /**
     * Renders a text usign Zend_Markup
     *
     * @return GlassOnion_View_Helper_Markup Provides a fluent interface
     */
    public function markup($text, $parser = 'Bbcode')
    {
        $this->_value = $this->_format($text, $parser);
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
