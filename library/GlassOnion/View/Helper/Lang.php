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
class GlassOnion_View_Helper_Lang extends Zend_View_Helper_Abstract
{
    /**
     * @const string
     */
    const DEFAULT_LANGUAGE = 'en';

    /**
     * @var string
     */
    private $_locale;

    /**
     * Returns the current locale language
     *
     * @return string
     */
    public function lang()
    {
        $locale = $this->getLocale();
        if (is_null($locale)) {
            return self::DEFAULT_LANGUAGE;
        }
        return $locale->getLanguage();
    }

    /**
     * Returns the locale
     *
     * @return string
     */
    public function getLocale()
    {
        if (null == $this->_locale) {
            require_once 'Zend/Registry.php';
            if (Zend_Registry::isRegistered('Zend_Locale')) {
                $this->_locale = Zend_Registry::get('Zend_Locale');
            }
        }
        return $this->_locale;
    }
}