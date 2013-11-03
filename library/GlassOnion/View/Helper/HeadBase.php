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
class GlassOnion_View_Helper_HeadBase extends Zend_View_Helper_Abstract
{
    protected $_request;
    protected $_scheme;
    protected $_host;
    protected $_baseUrl;

    /**
     * @return GlassOnion_View_Helper_HeadBase
     */
    public function headBase()
    {
        return $this;
    }
    
    /**
     * @return string
     */
    public function getRequest()
    {
        if ($this->_request === null) {
            $this->_request = Zend_Controller_Front::getInstance()->getRequest();
        }
        
        return $this->_request;
    }
    
    /**
     * @return string
     */
    public function getScheme()
    {
        if ($this->_scheme === null) {
            $this->_scheme = $this->getRequest()->getScheme();
        }
        
        return $this->_scheme;
    }   
    
    /**
     * @return GlassOnion_View_Helper_HeadBase Provides a fluent interface
     */
    public function setScheme($scheme)
    {
        $this->_scheme = (string) $scheme;
        return $this;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        if ($this->_host === null) {
            try {
                $this->_host = $this->getRequest()->getHttpHost();
            }
            catch (Exception $e) {
                throw new Exception('Could not determine host: ' . $e->getMessage());
            }
        }
        
        return $this->_host;
    }
    
    /**
     * @return GlassOnion_View_Helper_HeadBase Provides a fluent interface
     */
    public function setHost($host)
    {
        $this->_host = (string) $host;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getBaseUrl()
    {
        if ($this->_baseUrl === null) {
            try {
                $this->_baseUrl = $this->getRequest()->getBaseUrl();
            }
            catch (Exception $e) {
                throw new Exception('Could not determine baseUrl: ' . $e->getMessage());
            }
        }
        
        return $this->_baseUrl;
    }

    /**
     * @return GlassOnion_View_Helper_HeadBase Provides a fluent interface
     */
    public function setBaseUrl($baseUrl)
    {
        $this->_baseUrl = (string) $baseUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->getScheme() . '://' . $this->getHost() . $this->getBaseUrl() . '/';
    }

    /**
     * @return string
     */
    public function getEndTag()
    {
        return $this->view->doctype()->isXhtml() ? '/>' : '>';
    }
    
    /**
     * @return string
     */
    public function toString()
    {
        return '<base href="' . $this->getUrl() . '"' . $this->getEndTag() . PHP_EOL;
    }
    
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
}