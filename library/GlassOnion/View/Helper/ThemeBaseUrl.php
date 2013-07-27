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
class GlassOnion_View_Helper_ThemeBaseUrl
    extends Zend_View_Helper_Abstract
{
    /**
     * @const string
     */
    const DEFAULT_THEME = 'default';

    /**
     * @const string
     */
    const DEFAULT_BASE_URL = '/themes';

    /**
     * @var string
     */
    protected $_theme;
    
    /**
     * @var string
     */
    protected $_baseUrl;

    /**
     * Returns the theme's base url or file with the base url prepended
     *
     * @param string $theme
     * @return string
     */
    public function themeBaseUrl($file = null)
    {
        $path = $this->getBaseUrl() . '/' . $this->getTheme() . '/' . $file;
        return $this->view->baseUrl($path);
    }

    /**
     * Set the theme name
     *
     * @param string $theme
     * @return GlassOnion_View_Helper_ThemeBaseUrl Provides a fluent interface
     */
    public function setTheme($theme)
    {
        $this->_theme = $theme;
        return $this;
    }

    /**
     * Returns the theme name
     *
     * @return string
     */
    public function getTheme()
    {
        if (null === $this->_theme) {
            $this->_theme = self::DEFAULT_THEME;
        }
        return $this->_theme;
    }

    /**
     * Set the theme base URL
     *
     * @param string $baseUrl
     * @return GlassOnion_View_Helper_ThemeBaseUrl Provides a fluent interface
     */
    public function setBaseUrl($baseUrl)
    {
        $this->_baseUrl = rtrim($baseUrl, '/\\');
        return $this;
    }

    /**
     * Returns the theme base URL
     *
     * @return string
     */
    public function getBaseUrl()
    {
        if (null === $this->_baseUrl) {
            $this->_baseUrl = self::DEFAULT_BASE_URL;
        }
        return $this->_baseUrl;
    }
}
