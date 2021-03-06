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
class GlassOnion_View_Helper_Favicon
    extends Zend_View_Helper_Abstract
{
    /**
     * @see string
     */
    const DEFAULT_HREF = 'favicon.ico';

    /**
     * @see string
     */
    const DEFAULT_TYPE = 'image/x-icon';

    /**
     * @see string
     */
    const DEFAULT_PLACEMENT = Zend_View_Helper_Placeholder_Container_Abstract::APPEND;

    /**
     * Appends a theme's favicon to the head link placeholder 
     *
     * @param string $href
     * @param string $placement
     * @return Zend_View
     */
    public function favicon($href = self::DEFAULT_HREF, $type = self::DEFAULT_TYPE, $placement = self::DEFAULT_PLACEMENT)
    {
        $url = preg_match('/^(ht|f)tp(s)*:\/\/|^\//', $href)
            ? $href : $this->view->baseUrl($href);
        $this->view->headLink(array('rel' => 'shortcut icon', 'href' => $url, 'type' => $type));
        return $this->view;
    }
}