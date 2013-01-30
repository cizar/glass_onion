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
 * @see Zend_Application_Resource_ResourceAbstract
 */
require_once 'Zend/Application/Resource/ResourceAbstract.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_Application
 */
class GlassOnion_Application_Resource_Theme
    extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * @var string
     */
    protected $_theme;

    /**
     * @var string
     */
    protected $_baseUrl;

    /**
     * Defined by Zend_Application_Resource_Resource
     *
     * @return string
     */
    public function init()
    {
        $theme = $this->getTheme();

        // Optionally seed the ThemeBaseUrl view helper 
        $bootstrap = $this->getBootstrap();
        if ($bootstrap->hasResource('view')) {
            $bootstrap->bootstrap('view')
                ->getResource('view')
                ->getHelper('themeBaseUrl')
                ->setTheme($theme)
                ->setBaseUrl($this->getBaseUrl());
        }

        return $theme;
    }

    /**
     * Returns the defined theme name
     *
     * @return string
     */
    public function getTheme()
    {
        if (null === $this->_theme) {
            $options = $this->getOptions();
            $this->_theme = isset($options['name'])
                ? $options['name'] : 'default';
        }
        return $this->_theme;
    }
    
    /**
     * Returns the defined theme base url
     *
     * @return string
     */
    public function getBaseUrl()
    {
        if (null === $this->_baseUrl) {
            $options = $this->getOptions();
            $this->_baseUrl = isset($options['base_url'])
                ? $options['base_url'] : '/themes';
        }
        return $this->_baseUrl;
    }
}
