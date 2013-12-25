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
 * @see ZendX_JQuery
 */
require_once 'ZendX/JQuery.php';

/**
 * @see ZendX_JQuery_View_Helper_JQuery
 */
require_once 'ZendX/JQuery/View/Helper/JQuery.php';

/**
 * @see ZendX_JQuery_View_Helper_DatePicker
 */
require_once 'ZendX/JQuery/View/Helper/DatePicker.php';

/**
 * @see Zend_View_Helper_FormElement
 */
require_once 'Zend/View/Helper/FormElement.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_View
 * @subpackage Helper
 */
class GlassOnion_View_Helper_FormDate extends Zend_View_Helper_FormElement
{
	/**
	 * @var array
	 */
	protected $_info;

    /**
     * @return string
     */
    public function formDate($name, $value = null, $params = null, $attribs = null)
    {
        $this->_info = array(
            'formated' => $this->_getInfo($name . '-datepicker', $value, $attribs),
            'normalized' => $this->_getInfo($name, $value, $attribs)
        );
    	if (!isset($params['dateFormat']) && Zend_Registry::isRegistered('Zend_Locale')) {
    		$params['dateFormat'] = ZendX_JQuery_View_Helper_DatePicker::resolveZendLocaleToDatePickerFormat();
    	}
        $params['altField'] = '#' . $this->_info['normalized']['id'];
        $params['altFormat'] = 'yy-mm-dd';
    	$this->view->jQuery()->addOnLoad(sprintf('%s("#%s").datepicker(%s);',
            ZendX_JQuery_View_Helper_JQuery::getJQueryHandler(),
            $this->_info['formated']['id'],
            is_null($params) ? '' : ZendX_JQuery::encodeJson($params)
        ));
    	return $this;
    }

    /**
     * Return the complete HTML element tag
     *
     * @return string
     */
    public function __toString()
    {
        $string = $this->_hidden($this->_info['normalized']['name'],
            $this->_info['normalized']['value'], array('id' => $this->_info['normalized']['id']));

        $string .= sprintf('<input type="text" name="%s" id="%s" value="%s"%s%s autocomplete="off"%s',
        	$this->view->escape($this->_info['formated']['name']),
        	$this->view->escape($this->_info['formated']['id']),
        	$this->view->escape($this->view->date($this->_info['formated']['value'])),
        	$this->_info['formated']['disable'] ? ' disabled="disabled"' : '',
            $this->_htmlAttribs($this->_info['formated']['attribs']),
            $this->getClosingBracket());

        return $string;
    }
}
