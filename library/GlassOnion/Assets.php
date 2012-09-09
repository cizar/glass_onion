<?php

/**
 * @category   GlassOnion
 * @package    GlassOnion_Assets
 */
class GlassOnion_Assets
{
    /**
     * The Dependency Container Factory
     *
     * @return GlassOnion_Assets_Container
     */
    public static function createContainer()
    {
        require_once 'GlassOnion/Assets/Container.php';
        return new GlassOnion_Assets_Container();
    }
    
    /**
     * The Asset Definition Factory
     *
     * @return GlassOnion_Di_Definition
     */
    public static function createAsset()
    {
        $args = func_get_args();
        require_once 'GlassOnion/Assets/Asset.php';
        $class = new ReflectionClass('GlassOnion_Assets_Asset');
        return $class->newInstanceArgs($args);
    }
}
