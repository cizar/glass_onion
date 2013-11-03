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
class GlassOnion_View_Helper_Percent
    extends Zend_View_Helper_Abstract
{
    /**
     * @var integer
     */
    private $_decimals = 0;

    /**
     * @var float
     */
    private $_value = null;

    /**
     * Returns the formatted percent value
     *
     * @return GlassOnion_View_Helper_Percent
     */
    public function percent($data = null, $decimals = null)
    {
        $this->_value = $this->_format($data, $decimals);
        return $this;
    }

    /**
     * Sets the percent decimals
     *
     * @return GlassOnion_View_Helper_Percent Provides a fluent interface
     */
    public function setDecimals($decimals)
    {
        $this->_decimals = $decimals;
        return $this;
    }

    /**
     * Returns the formated string.
     *
     * @return string
     */
    private function _format($data = null, $decimals = null)
    {
        if (null === $data) {
            return '';
        }

        $percent = $this->_getPercent($data);

        if (null === $decimals) {
            $decimals = $this->_decimals;
        }

        return $this->view->number($percent, $decimals) . '%';
    }

    /**
     * Returns the percent value
     *
     * @return float
     */
    private function _getPercent($data)
    {
        if (is_array($data)) {
            list($value, $max) = $data;

            if ($max == 0) {
                return 0;
            }

            return $value / $max * 100;
        }
        else if (!is_numeric($data)) {
            throw new Zend_View_Exception('Data must be a numeric or array of [value, max].');
        }

        return (float) $data * 100;
    }

    /**
     * Cast to string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->_value;
    }
}