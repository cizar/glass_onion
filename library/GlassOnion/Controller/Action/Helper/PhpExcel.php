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
 * @see PHPExcel_IOFactory
 */
require_once 'PHPExcel/PHPExcel/IOFactory.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_Controller
 * @subpackage Helper
 */
class GlassOnion_Controller_Action_Helper_PhpExcel
    extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * The default file name.
     */
    const DEFAULT_FILENAME = 'data.xsl';

    /**
     * Returns the identity of the authenticated user
     *
     * @param string $field
     * @return mixed
     */
    public function direct(PHPExcel $excel, $filename = null)
    {
        if (null === $filename) {
            $filename = self::DEFAULT_FILENAME;
        }

        $response = $this->getResponse();

        $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

        ob_start();
        $writer->save('php://output');
        $content = ob_get_clean();

        $response->setHttpResponseCode(200)
                 ->clearAllHeaders()
                 ->setHeader('Content-Type', 'application/vnd.ms-excel')
                 ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
                 ->setHeader('Content-Length', strlen($content))
                 ->setBody($content)
                 ->sendResponse();

        exit;
    }
}
