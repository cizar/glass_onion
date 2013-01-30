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
class GlassOnion_View_Helper_Asset
    extends Zend_View_Helper_Abstract
{
    /**
     * Appends an asset and all its dependencies
     *
     * @param string $id
     * @return Zend_View
     */
    public function asset()
    {
        $ids = func_get_args();

        $assets = Zend_Controller_Front::getInstance()
            ->getParam('bootstrap')
            ->getResource('assets');
        
        foreach ($ids as $id) {
            foreach ($assets->getRequiredAssets($id) as $asset) {
                switch ($asset->getClass()) {
                    case 'script':
                        $this->view->script($asset->src,
                            $asset->getProperty('type', 'text/javascript'));
                        break;
                    case 'stylesheet':
                        $this->view->stylesheet($asset->href,
                            $asset->getProperty('media', 'screen'));
                        break;
                    case 'favicon':
                        $this->view->favicon($asset->href);
                        break;
                }
            }
        }

        return $this->view;
    }
}
