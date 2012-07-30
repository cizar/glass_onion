<?php

/**
 * @category   GlassOnion
 * @package    GlassOnion_Di
 */
class GlassOnion_Di
{
    /**
     * The Dependency Injection Container Factory
     *
     * @return GlassOnion_Di_Container
     */
    public static function createContainer()
    {
        require_once 'GlassOnion/Di/Container.php';
        return new GlassOnion_Di_Container();
    }

    /**
     * The Dependency Injection Resource Definition Factory
     *
     * @return GlassOnion_Di_Definition
     */
    public static function createDefinition()
    {
        $args = func_get_args();
        require_once 'GlassOnion/Di/Definition.php';
        $class = new ReflectionClass('GlassOnion_Di_Definition');
        return $class->newInstanceArgs($args);
    }

    /**
     * The Dependency Injection Resource Reference Factory
     *
     * @param string $id
     * @return GlassOnion_Di_Reference
     */
    public static function createReference($id)
    {
        require_once 'GlassOnion/Di/Reference.php';
        return new GlassOnion_Di_Reference($id);
    }

    /**
     * The Dependency Injection Resource Parameter Factory
     *
     * @param string $id
     * @return GlassOnion_Di_Parameter
     */
    public static function createParameter($id)
    {
        require_once 'GlassOnion/Di/Parameter.php';
        return new GlassOnion_Di_Parameter($id);
    }

    /**
     * The Dependency Injection Resource Global Factory
     *
     * @param string $id
     * @return GlassOnion_Di_Global
     */
    public static function createGlobal($id)
    {
        require_once 'GlassOnion/Di/Global.php';
        return new GlassOnion_Di_Global($id);
    }
}
