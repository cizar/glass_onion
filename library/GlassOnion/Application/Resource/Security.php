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
class GlassOnion_Application_Resource_Security
    extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * @var Zend_Acl
     */
    protected $_acl = null;

    /**
     * Defined by Zend_Application_Resource_Resource
     *
     * @return void
     */
    public function init()
    {
        Zend_Controller_Front::getInstance()->registerPlugin($this->getPlugin());
    }

    public function getPlugin()
    {
        $plugin = new GlassOnion_Controller_Plugin_AccessControl();
        $plugin->setAcl($this->getAcl());
        $plugin->setAccessDeniedPage($this->getLoginPage());
        return $plugin;
    }

    public function getAcl()
    {
        $options = $this->getOptions();
        $class = new ReflectionClass($options['acl']);
        return $class->newInstance();
    }

    public function getLoginPage()
    {
        $options = $this->getOptions();
        $page = array();
        foreach (array('action', 'controller', 'module') as $i => $part) {
            $page[$i] = isset($options['login'][$part]) ? $options['login'][$part] : null;
        }
        return $page;
    }
}