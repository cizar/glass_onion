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
 * @see Zend_Controller_Plugin_Abstract
 */
require_once 'Zend/Controller/Plugin/Abstract.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_Controller
 * @subpackage Plugin
 */
class GlassOnion_Controller_Plugin_Segments
    extends Zend_Controller_Plugin_Abstract
{
    /**
     * @var array
     */
    private $_segments = array();

    /**
     * Appends an action to be rendered as a block into a segment
     *
     * @param string $segment
     * @param string $action
     * @param string $controller
     * @param string $module
     * @param array $params
     * @param string $id
     * @return GlassOnion_Controller_Plugin_Segments Provides a fluent interface
     */
    public function appendToSegment($segment, $action, $controller,
        $module = null, array $params = array(), $id = null)
    {
        if (!isset($this->_segments[$segment])) {
            $this->_segments[$segment] = array();
        }
        $this->_segments[$segment][] = array('id' => $id,
            'action' => array($action, $controller, $module, $params));
    }

    /**
     * Before the dispatch loop, add call some actions
     *
     * @param Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
        if ($request->isXmlHttpRequest()) {
            return;
        }
        foreach ($this->_segments as $segmentName => $blocks) {
            foreach ($blocks as $block) {
                $content = $this->_render($block['action']);
                if ($block['id']) {
                    $content = "<div id=\"{$block['id']}\">{$content}</div>";
                }
                $this->getResponse()->appendBody($content, $segmentName);
            }
        }
    }

    /**
     * Retrieve rendered contents of a controller action
     *
     * @param array $action
     * @return string
     */
    private function _render($action)
    {
        $view = Zend_Layout::getMvcInstance()->getView();
        return call_user_func_array(array($view, 'action'), $action);
    }
}