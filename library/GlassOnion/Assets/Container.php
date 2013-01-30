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
            throw new GlassOnion_Assets_Exception("Failed to load the config file: " . $ex->getMessage());
        }
    }
}
