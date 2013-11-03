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
 * @see Zend_View_Helper_FormElement
 */
require_once 'Zend/View/Helper/FormElement.php';

class GlassOnion_View_Helper_FieldErrors
    extends Zend_View_Helper_FormElement
{
    /**
     * Returns a form label with the record error stack (Only for Doctrine)
     *
     * @param string|array $fields
     * @param Doctrine_Record $record
     * @return Zend_View_Helper_FormLabel
     */
    public function fieldErrors($fields, Doctrine_Record $record = null)
    {
        if (null === $record) {
            $record = $this->view->record;
        }
        
        $errors = array();

        foreach ((array) $fields as $field) {
            if (!is_string($field)) {
                /**
                 * @see Zend_View_Exception
                 */
                require_once 'Zend/View/Exception.php';
                throw new Zend_View_Exception('The field name must be string');
            }
            if ($fieldErrors = $record->getErrorStack()->get($field)) {
                $errors = array_merge($errors, $fieldErrors);
            }
        }

        if (!$errors) {
            return '';
        }

        return $this->view->doctrineErrors($errors);
    }
}
