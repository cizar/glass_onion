<?php

/**
 * Glass Onion
 *
 * Copyright (c) 2009 César Kästli (cesarkastli@gmail.com)
 *
 * Permission is hereby granted, free of charge, to any
 * person obtaining a copy of this software and associated
 * documentation files (the "Software"), to deal in the
 * Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the
 * Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice
 * shall be included in all copies or substantial portions of
 * the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY
 * KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR
 * PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS
 * OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR
 * OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
 * OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * @copyright  Copyright (c) 2009 César Kästli (cesarkastli@gmail.com)
 * @license    MIT
 */

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
     * @const string
     */
    const DEFAULT_PARSER = 'Bbcode';

    /**
     * @var string
     */
    private $_value;

    /**
     * @var string
     */
    private $_parser;

    /**
     * Renders a text usign Zend_Markup
     *
     * @return GlassOnion_View_Helper_Markup Provides a fluent interface
     */
    public function markup($value, $parser = null)
    {
        if (null !== $parser) {
            $this->setParser($parser);
        }
        return $this->setValue($value);
    }
    
    /**
     * @return string
     */
    public function getValue()
    {
        return $this->_value;
    }

    /**
     * @return GlassOnion_View_Helper_Markup Provides a fluent interface
     */
    public function setValue($value)
    {
        $this->_value = $value;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getParser()
    {
        if (null === $this->_parser) {
            $this->_parser = self::DEFAULT_PARSER;
        }
        return $this->_parser;
    }

    /**
     * @return GlassOnion_View_Helper_Markup Provides a fluent interface
     */
    public function setParser($parser)
    {
        $this->_parser = $parser;
        return $this;
    }

    /**
     * Cast to string
     *
     * @return string
     */
    public function __toString()
    {
        try {
            $value = $this->getValue();
            if (empty($value)) {
                return '';
            }
            $markup = Zend_Markup::factory($this->getParser(), 'Html');
            return $markup->render($value);
        }
        catch (Exception $e) {
            return get_class($e) . ' ' . $e->getMessage();
        }
    }
}
