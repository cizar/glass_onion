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
class GlassOnion_View_Helper_Number extends Zend_View_Helper_Abstract
{
    /**
     * @const integer
     */
    const DEFAULT_PRECISION = 0;

    /**
     * @const string
     */
    const DEFAULT_LOCALE = 'en_US';

    /**
     * @const string
     */
    const DEFAULT_FORMAT = '#,##0.#';

    /**
     * @var numeric
     */
    private $_value;

    /**
     * @var integer
     */
    private $_precision;

    /**
     * @var string
     */
    private $_locale;

    /**
     * @var string
     */
    private $_format;

    /**
     * Helper entry point
     *
     * @return GlassOnion_View_Helper_Number Provides a fluent interface
     */
    public function number($value, $precision = null, $locale = null, $format = null)
    {
        $this->_value = $value;
        $this->_precision = $precision;
        $this->_locale = $locale;
        $this->_format = $format;
        return $this;
    }

    /**
     * Returns the number to being formatted
     *
     * @return numeric
     */
    public function getValue()
    {
        return $this->_value;
    }

    /**
     * Sets the number to being formatted
     *
     * @return GlassOnion_View_Helper_Number Provides a fluent interface
     */
    public function setValue($value)
    {
        if (!is_numeric($value)) {
            throw new Zend_View_Exception('The value must be numeric.');
        }
        $this->_value = $value;
        return $this;
    }

    /**
     * Returns the decimal precision of the number
     *
     * @return integer
     */
    public function getPrecision()
    {
        if (null === $this->_precision) {
            return self::DEFAULT_PRECISION;
        }
        return $this->_precision;
    }

    /**
     * Sets the decimal precision of the number
     *
     * @return GlassOnion_View_Helper_Number Provides a fluent interface
     */
    public function setPrecision($precision)
    {
        if (!is_integer($precision) || $precision < 0) {
            throw new Zend_View_Exception('The precision value must be integer and positive.');
        }
        $this->_precision = $precision;
        return $this;
    }

    /**
     * Returns the locale
     *
     * @return string
     */
    public function getLocale()
    {
        if (null !== $this->_locale) {
            return $this->_locale;
        }

        require_once 'Zend/Registry.php';
        if (Zend_Registry::isRegistered('Zend_Locale')) {
            return Zend_Registry::get('Zend_Locale');
        }
        
        return self::DEFAULT_LOCALE;
    }

    /**
     * Sets the locale
     *
     * @return GlassOnion_View_Helper_Number Provides a fluent interface
     */
    public function setLocale($locale)
    {
        if (!Zend_Locale::isLocale($locale)) {
            throw new Zend_View_Exception('The given locale is not valid.');
        }
        $this->_locale = $locale;
        return $this;
    }

    /**
     * Sets the number format
     *
     * @return GlassOnion_View_Helper_Number Provides a fluent interface
     */
    public function setNumberFormat($format)
    {
        $this->_format = $format;
        return $this;
    }

    /**
     * Returns the number format
     *
     * @return string
     */
    public function getNumberFormat()
    {
        if (null === $this->_format) {
            return self::DEFAULT_FORMAT;
        }
        return $this->_format;
    }

    /**
     * Cast to string
     *
     * @return string
     */
    public function __toString()
    {
        require_once 'Zend/Locale/Format.php';
        return Zend_Locale_Format::toNumber($this->getValue(),
            array('precision' => $this->getPrecision(), 'number_format' => $this->getNumberFormat(), 'locale' => $this->getLocale()));
    }
}