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
class GlassOnion_View_Helper_Date extends Zend_View_Helper_Abstract
{
    /**
     * @const string
     */
    const DEFAULT_LOCALE = 'en_US';

    /**
     * @var string
     */
    private $_value = null;

    /**
     * @var string
     */
    private $_locale = null;

    /**
     * Returns a formated date
     *
     * @param string $value
     * @param string $locale
     * @return GlassOnion_View_Helper_Date Provides a fluent interface
     */
    public function date($value, $locale = null)
    {
        $this->setValue($value);
        if (null !== $locale) {
            $this->setLocale($locale);
        }
        return $this;
    }

    /**
     * Checks if a value has been defined
     *
     * @return boolean
     */
    public function hasValue()
    {
        return null !== $this->_value;
    }

    /**
     * Returns the date value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->_value;
    }

    /**
     * Returns a formated date
     *
     * @param string $value
     * @return GlassOnion_View_Helper_Date Provides a fluent interface
     */
    public function setValue($value)
    {
        $this->_value = $value;
        return $this;
    }

    /**
     * Returns the locale
     *
     * @return string
     */
    public function getLocale()
    {
        if (null === $this->_locale) {
            require_once 'Zend/Registry.php';
            if (Zend_Registry::isRegistered('Zend_Locale')) {
                $this->_locale = Zend_Registry::get('Zend_Locale');
            } else {
                $this->_locale = self::DEFAULT_LOCALE;
            }
        }
        return $this->_locale;
    }

    /**
     * Returns the locale
     *
     * @param string $locale
     * @return GlassOnion_View_Helper_Date Provides a fluent interface
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
     * Cast to string
     *
     * @return string
     */
    public function __toString()
    {
        if ($this->hasValue()) {
            require_once 'Zend/Date.php';
            $date = new Zend_Date($this->getValue(), 'Y-m-d');
            return $date->toString(Zend_Date::DATES, null, $this->getLocale());
        }
        return '';
    }
}