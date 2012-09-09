<?php

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
