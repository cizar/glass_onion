<?php

/**
 * @category   GlassOnion
 * @package    GlassOnion_Assets
 */
class GlassOnion_Assets_Container
{
    /**
     * @var array
     */
    private $_assets;
    
    /**
     * @param string $id
     * @return boolean
     */
    public function hasAsset($id)
    {
        return array_key_exists($id, $this->_assets);
    }

    /**
     * @param string $id
     * @return GlassOnion_Assets_Asset
     */
    public function getAsset($id)
    {
        if (!$this->hasAsset($id)) {
            throw new InvalidArgumentException("The asset \"{$id}\" could not be found");
        }
        return $this->_assets[$id];
    }
        
    /**
     * @param string $id
     * @param GlassOnion_Assets_Asset $asset
     * @return GlassOnion_Assets_Container Provides a fluent interface
     */
    public function addAsset($id, GlassOnion_Assets_Asset $asset)
    {
        $this->_assets[$id] = $asset;
    }

    /**
     * @param string $id
     * @return array
     */    
    public function getDependencies($id)
    {
        return $this->getAsset($id)->getDependencies();
    }
    
    /**
     * @param string $id
     * @return array
     */
    public function getRequiredAssets($id)
    {
        $assets = array();
        foreach ($this->getDependencies($id) as $depId) {
            foreach ($this->getRequiredAssets($depId) as $dep) {
                $assets[] = $dep;
            }
        }
        $assets[] = $this->getAsset($id);
        return $assets;
    }
    
    /**
     * Factory
     *
     * @param string $filename
     * @return GlassOnion_Assets_Container
     */    
    public static function fromYaml($filename)
    {
        try {
            /**
             * @see Zend_Config_Yaml
             */
            require_once 'Zend/Config/Yaml.php';
            $yaml = new Zend_Config_Yaml($filename, 'assets');
            /**
             * @see GlassOnion_Assets_Asset
             */
            require_once 'GlassOnion/Assets/Asset.php';
            $container = new self;
            foreach ($yaml as $id => $config) {
                $container->addAsset($id, GlassOnion_Assets_Asset::fromConfig($config));
            }
            return $container;
        } catch (Zend_Config_Exception $ex) {
            /**
             * @see GlassOnion_Assets_Exception
             */
            require_once 'GlassOnion/Assets/Exception.php';
            throw new GlassOnion_Assets_Exception("Failed to load config file $filename");
        }
    }
}
