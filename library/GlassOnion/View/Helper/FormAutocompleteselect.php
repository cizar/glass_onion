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
 * @see ZendX_JQuery_View_Helper_JQuery
 */
require_once 'ZendX/JQuery/View/Helper/JQuery.php';

/**
 * @see Zend_View_Helper_FormElement
 */
require_once 'Zend/View/Helper/FormElement.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_View
 * @subpackage Helper
 */
class GlassOnion_View_Helper_FormAutocompleteselect extends Zend_View_Helper_FormElement
{
    /**
     * @return string
     */
    public function formAutocompleteselect($name, $value = null, $attribs = null,
        $options = null, $listsep = "<br />\n")
    {
    	$info = $this->_getInfo($name, $value, $attribs, $options, $listsep);
    	$js = sprintf('%s("#%s").autocompleteselect();',
    		ZendX_JQuery_View_Helper_JQuery::getJQueryHandler(),
    		$info['id']
    	);
    	$this->view->jQuery()->addOnLoad($js);
    	return $this->view->formSelect($name, $value, $attribs, $options, $listsep);
    }
}