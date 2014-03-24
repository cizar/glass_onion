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
 * @see GlassOnion_Di_Definition
 */
require_once 'GlassOnion/Di/Definition.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_Di
 */
class GlassOnion_Di_Container
{
    /**
     * @var array
     */
    private $definitions = array();

    /**
     * @var array
     */
    private $shared = array();

    /**
     * @var array
     */
    private $parameters = array();

    /**
     * @param string $id
     * @return boolean
     */
    public function hasDefinition($id)
    {
        return array_key_exists($id, $this->definitions);
    }

    /**
     * @param string $id
     * @return GlassOnion_Di_Definition
     */
    public function getDefinition($id)
    {
        if (!$this->hasDefinition($id)) {
            throw new InvalidArgumentException("A definition for '$id' could not be found");
        }
        return $this->definitions[$id];
    }

    /**
     * @param string $id
     * @param GlassOnion_Di_Definition $definition
     * @return GlassOnion_Di_Container Provides a fluent interface
     */
    public function addDefinition($id, GlassOnion_Di_Definition $definition)
    {
        $this->definitions[$id] = $definition;
        return $this;
    }

    /**
     * @param string $id
     * @return object
     */
    public function getResource($id)
    {
        if (array_key_exists($id, $this->shared)) {
            return $this->shared[$id];
        }

        $definition = $this->getDefinition($id);

        $classname = $definition->getClass();
        require_once 'Zend/Loader.php';
        Zend_Loader::loadClass($classname);
        $class = new ReflectionClass($classname);

        if (NULL === $class->getConstructor()) {
            $instance = $class->newInstance();
        } else {
            $args = $this->evaluateArguments($definition->getArguments());
            $instance = $class->newInstanceArgs($args);
        }

        foreach ($definition->getMethodInvokes() as $method => $arguments) {
            if (!method_exists($instance, $method)) {
                throw new InvalidArgumentException("The resource method '$method' not exists");
            }
            $args = $this->evaluateArguments($arguments);
            call_user_func_array(array($instance, $method), $args);
        }

        if ($definition->isShared()) {
            $this->shared[$id] = $instance;
        }

        return $instance;
    }   

    /**
     * @param string $id
     * @return object
     */
    public function __get($id)
    {
        return $this->getResource($id);
    }

    /**
     * @param array|mixed $args
     * @return array|mixed
     */
    public function evaluateArguments($args)
    {
        if (is_array($args)) {
            return array_map(array($this, __FUNCTION__), $args);
        }

        if (is_object($args)) {
            switch (get_class($args)) {
                case 'GlassOnion_Di_Reference':
                    return $this->getResource((string) $args);
                case 'GlassOnion_Di_Parameter':
                    return $this->getParameter((string) $args);
                case 'GlassOnion_Di_Global':
                    return $this->getGlobal((string) $args);
            }
        }

        return $args;
    }

    /**
     * Returns a parameter value
     *
     * For path 'foo.bar' will return $this->parameters['foo']['bar']
     *
     * @param string $id
     * @return mixed
     */
    public function getParameter($id)
    {
        $parameters = $this->parameters;
        $path = explode('.', $id);
        foreach ($path as $key) {
            if (!array_key_exists($key, $parameters)) {
                throw new InvalidArgumentException("The resource parameter '$id' not exists");
            }
            $parameters = $parameters[$key];            
        }
        return $parameters;
    }

    /**
     * @param string $id
     * @param scalar $value
     * @return GlassOnion_Di_Container Provides a fluent interface
     */
    public function setParameter($id, $value)
    {
        if (!is_scalar($value)) {
            throw new InvalidArgumentException('The value must be a scalar');
        }
        eval('$this->parameters["' . implode('"]["',explode('.', $id)) . '"] = unserialize(\'' . serialize($value) . '\');');
        return $this;
    }

    /**
     * @param array $parameters
     * @return GlassOnion_Di_Container Provides a fluent interface
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * Returns a global value
     *
     * @param string $id
     * @return mixed
     */
    public function getGlobal($id)
    {
        // Using global variables is discouraged
        trigger_error('Please, don\'t use globals!', E_USER_WARNING);
        if (!array_key_exists($id, $GLOBALS)) {
            throw new InvalidArgumentException("Unknown global '$id'");
        }
        return $GLOBALS[$id];
    }

}