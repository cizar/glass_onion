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
 * @see Zend_Controller_Action_Helper_Abstract
 */
require_once 'Zend/Controller/Action/Helper/Abstract.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_Controller
 * @subpackage Helper
 */
class GlassOnion_Controller_Action_Helper_TransferContent
    extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * Start a file transfer
     *
     * @param string $content
     * @param string $contentType
     * @param string $filename
     * @param array $options
     * @return void
     */
    public function direct($content, $contentType = 'text/plain', $filename = 'unknown', $options = array())
    {
        $response = $this->getResponse();
        
        if (!$response->canSendHeaders()) {
            return false;
        }
        
        if (isset($options['disposition']) && 'inline' === $options['disposition']) {
            $disposition = 'inline';
        } else {
            $disposition = 'attachment';
        }
                    
        $response->setHttpResponseCode(200)
                 ->setHeader('Content-Type', $contentType, true)
                 ->setHeader('Content-Disposition',
                    $disposition . '; filename="' . $filename . '"', true)
                 ->setHeader('Content-Length', strlen($content), true)
                 ->setBody($content)
                 ->sendResponse();

        exit;
    }
}