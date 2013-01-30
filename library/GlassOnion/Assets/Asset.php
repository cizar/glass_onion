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
 * @category   GlassOnion
 * @package    GlassOnion_Assets
 */
class GlassOnion_Assets_Asset
{    
    /**
     * @var string
     */
    private $_class;
    
    /**
     * @var array
     */
    private $_properties;
    
    /**
     * @var array
     */
    private $_dependencies;
    
    /**
     * @return string
     */
    public function getClass()
    {
        return $this->_class;
    }
    
    /**
     * @param string $key
     * @return boolean
     */
    public function __isset($key)
    {
        return array_key_exists($key, $this->_properties);
    }
    
    /**
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        if (!$this->__isset($key)) {
            /**
             * @see GlassOnion_Assets_Exception
             */
            require_once 'GlassOnion/Assets/Exception.php';
            throw new GlassOnion_Assets_Exception("The property $key does not exists");
        }
        return $this->_properties[$key];
    }
    
    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getProperty($key, $default = null)
    {
        if ($this->__isset($key)) {
            return $this->__get($key);
        }
        return $default;
    }
    
    /**
     * @return array
     */
    public function getDependencies()
    {
        return $this->_dependencies;
    }
    
    /**
     * @param string $id
     * @param string $type
     * @param array $dependencies
     */
    public function __construct($class = 'virtual', $properties = array(), $dependencies = array())
    {
        $this->_class = $class;
        $this->_properties = $properties;
        $this->_dependencies = $dependencies;
    }

    /**
     * Factory
     *
     * @param Zend_Config $config
     * @return GlassOnion_Assets_Asset
     */
    public static function fromConfig(Zend_Config $config)
    {
        $properties = $config->toArray();

        $class = null;
        
        if (isset($properties['class'])) {
            $class = $properties['class'];
            unset($properties['class']);
        }
        
        $depends = array();

        if (isset($properties['depends'])) {
            $depends = array_map('trim', explode(',', $properties['depends']));
            unset($properties['depends']);
        }
        
        return new self($class, $properties, $depends);
    }
}
