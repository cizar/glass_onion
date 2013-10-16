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
 * @see GlassOnion_Assets_Container
 */
require_once 'GlassOnion/Assets/Container.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_Application
 */
class GlassOnion_Application_Resource_Assets
    extends Zend_Application_Resource_ResourceAbstract
{
    private $_container;

    /**
     * Defined by Zend_Application_Resource_Resource
     *
     * @return GlassOnion_Assets_Container
     */
    public function init()
    {
        return $this->getContainer();
    }

    /**
     * Retrieve the assets container
     *
     * @return GlassOnion_Assets_Container
     */
    private function getContainer()
    {
        if (null == $this->_container) {
            $options = $this->getOptions();
            $this->_container = GlassOnion_Assets_Container::fromYaml($options['config']);
            
            $this->getBootstrap()->bootstrap('view');
            $view = $this->getBootstrap()->view;

            $view->addHelperPath('GlassOnion/Assets/View/Helper', 'GlassOnion_Assets_View_Helper');
            $view->asset()->setContainer($this->_container);
        }
        return $this->_container;
    }
}