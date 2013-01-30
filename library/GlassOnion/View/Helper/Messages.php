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
 * @see GlassOnion_View_Helper_Messages
 */
require_once 'GlassOnion/View/Helper/Messages.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_View
 * @subpackage Helper
 */
class GlassOnion_View_Helper_Messages
    extends GlassOnion_View_Helper_FlashMessenger
{
    /**
     * @const string
     */
    const DEFAULT_TEMPLATE = '<div class="%s">%s</div>';

    /**
     * @const string
     */
    const DEFAULT_STATUS = 'info';

    /**
     * Returns the Flash Messenger object.
     *
     * @return Zend_Controller_Action_Helper_FlashMessenger
     */
    public function messages($status = self::DEFAULT_STATUS, $template = self::DEFAULT_TEMPLATE)
    {
        if (null == $status) {
            $status = self::DEFAULT_STATUS;
        }

        if (null == $template) {
            $template = self::DEFAULT_TEMPLATE;
        }

        $flashMessenger = $this->flashMessenger();

        $messages = $flashMessenger->getMessages();

        if ($flashMessenger->hasCurrentMessages()) {
            $messages = array_merge($messages, $flashMessenger->getCurrentMessages());
            $flashMessenger->clearCurrentMessages();
        }

        $output ='';

        foreach ($messages as $message) {
            if (is_string($message)) {
                $message = array('status' => $status, 'message' => $message);
            }
            $output .= sprintf($template, $message['status'], $message['message']);
        }

        return $output;
    }
}