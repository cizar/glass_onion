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
class GlassOnion_View_Helper_SortUrl
    extends Zend_View_Helper_Abstract
{
    /**
     * Generates the sort url for a given field in a sort condition
     *
     * @param string $field
     * @param string|array $data
     * @param string $name
     * @param boolean $reset
     * @param boolean $encode
     * @return string
     */
    public function sortUrl($field, $data = null,
        $name = null, $reset = true, $encode = true)
    {
        if (null === $data && isset($this->view->sortData)) {
            $data = $this->view->sortData;
        }
        
        switch (gettype($data))
        {
            case 'string':
                $order = $data;
                break;
                
            case 'array':
                if ($field === $data['field']) {
                    $order = $this->_reverse($data['order']);
                    break;
                }
                // NO break.. go to default
                
            case 'NULL':
                $order = 'asc';
        }
        
        $options = array('sort' => $order . 'ending_by_' . $field);

        return $this->view->url($options, $name, $reset, $encode);
    }
    
    /**
     * Returns the reverse order of a given order
     *
     * @param string $order [asc|desc]
     * @return string
     */
    private function _reverse($order)
    {
        return ('asc' === $order) ? 'desc' : 'asc';
    }
}