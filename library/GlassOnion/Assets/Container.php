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
class GlassOnion_Assets_Container
{
    /**
     * @var array
     */
    private $_assets;
    
    /**
     * @param string $key
     * @return boolean
     */
    public function __isset($id)
    {
        return array_key_exists($id, $this->_assets);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function __get($id)
    {
        if (!$this->__isset($id)) {
            /**
             * @see GlassOnion_Assets_Exception
             */
            require_once 'GlassOnion/Assets/Exception.php';
            throw new GlassOnion_Assets_Exception("The asset \"{$id}\" could not be found");
        }
        return $this->_assets[$id];
    }

    /**
     * @param string $id
     * @return boolean
     */
    public function hasAsset($id)
    {
        return $this->__isset($id);
    }

    /**
     * @param string $id
     * @return GlassOnion_Assets_Asset
     */
    public function getAsset($id)
    {
        return $this->__get($id);
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
    public function getAssetDependencies($id)
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
        foreach ($this->getAssetDependencies($id) as $depId) {
            foreach ($this->getRequiredAssets($depId) as $dep) {
                $assets[] = $dep;
            }
        }
        $assets[] = $this->getAsset($id);
        return $assets;
    }

    /**
     * Factory (From Zend_Config)
     *
     * @param Zend_Config $config
     * @return GlassOnion_Assets_Container
     */
    public static function fromConfig(Zend_Config $config)
    {
        /**
         * @see GlassOnion_Assets_Asset
         */
        require_once 'GlassOnion/Assets/Asset.php';
        $container = new self;
        foreach ($config as $id => $assetConfig) {
            $container->addAsset($id,
                GlassOnion_Assets_Asset::fromConfig($assetConfig));
        }
        return $container;
    }
    
    /**
     * Factory (From YAML File)
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
            $config = new Zend_Config_Yaml($filename, 'assets');
            return self::fromConfig($config);
        } catch (Zend_Config_Exception $ex) {
            /**
             * @see GlassOnion_Assets_Exception
             */
            require_once 'GlassOnion/Assets/Exception.php';
            throw new GlassOnion_Assets_Exception("Failed to load the config file: " . $ex->getMessage());
        }
    }
}