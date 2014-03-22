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
 * @see Zend_Application_Resource_ResourceAbstract
 */
require_once 'Zend/Application/Resource/ResourceAbstract.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_Application
 */
class GlassOnion_Application_Resource_Segments
    extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * Defined by Zend_Application_Resource_Resource
     *
     * @return GlassOnion_Controller_Plugin_Segments
     */
    public function init()
    {
        $plugin = $this->_getPlugin();
        Zend_Controller_Front::getInstance()->registerPlugin($plugin);
        return $plugin;
    }

    /**
     * Returns the pre-configured Segments controller plugin
     *
     * @return GlassOnion_Controller_Plugin_Segments
     */
    private function _getPlugin()
    {
        $plugin = new GlassOnion_Controller_Plugin_Segments();
        foreach ($this->getOptions() as $segmentName => $blocks) {
            if (isset($blocks['action']) || isset($blocks['controller'])) {
                $blocks = array($blocks);
            }
            foreach ($blocks as $block => $config) {
                $id = is_string($block) ? $block : null;
                list($action, $controller, $module, $params) = $this->_parseActionConfig($config);
                $plugin->appendToSegment($segmentName, $action, $controller, $module, $params, $id);
            }
        }
        return $plugin;
    }

    /**
     * Parse the controller action from a hash array
     *
     * @param array
     * @return array
     */
    private function _parseActionConfig(array $config)
    {
        return array(
            isset($config['action']) ? $config['action'] : null,
            isset($config['controller']) ? $config['controller'] : null,
            isset($config['module']) ? $config['module'] : null,
            isset($config['params']) ? $config['params'] : array()
        );
    }
}