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
