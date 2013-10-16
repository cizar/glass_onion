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
    const DEFAULT_ASSET_CLASS = 'virtual'; 

    /**
     * @var string
     */
    const CONFIG_CLASS_KEY = 'class'; 

    /**
     * @var string
     */
    const CONFIG_DEPENDENCIES_KEY = 'depends'; 

    /**
     * @var string
     */
    private $_class = self::DEFAULT_ASSET_CLASS;
        
    /**
     * @var array
     */
    private $_dependencies = array();
    
    /**
     * @var array
     */
    private $_properties = array();

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->_class;
    }
        
    /**
     * @return array
     */
    public function getDependencies()
    {
        return $this->_dependencies;
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
        return $this->__isset($key) ? $this->__get($key) : $default;
    }
    
    /**
     * @param string $id
     * @param string $type
     * @param array $dependencies
     */
    public function __construct($class = null, $dependencies = null, $properties = null)
    {
        if (null != $class) {
            $this->_class = $class;
        }
        if (null != $dependencies) {
            $this->_dependencies = $dependencies;
        }
        if (null != $properties) {
            $this->_properties = $properties;
        }
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
        
        if (isset($properties[self::CONFIG_CLASS_KEY])) {
            $class = $properties[self::CONFIG_CLASS_KEY];
            unset($properties[self::CONFIG_CLASS_KEY]);
        }
        
        $dependencies = null;

        if (isset($properties[self::CONFIG_DEPENDENCIES_KEY])) {
            $dependencies = array_map('trim',
                explode(',', $properties[self::CONFIG_DEPENDENCIES_KEY]));
            unset($properties[self::CONFIG_DEPENDENCIES_KEY]);
        }
        
        return new self($class, $dependencies, $properties);
    }

    public function toString()
    {
        return __CLASS__ . ' ' . $this->_class;
    }
}