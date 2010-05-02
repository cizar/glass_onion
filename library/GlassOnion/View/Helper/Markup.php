<?php

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

class GlassOnion_View_Helper_Markup extends Zend_View_Helper_Abstract
{
    public function markup($text, $parser = 'Bbcode')
    {
        if (is_null($text) or empty($text))
        {
            return '';
        }

        $markup = Zend_Markup::factory($parser, 'Html');

        return $markup->render($text);
    }
}
