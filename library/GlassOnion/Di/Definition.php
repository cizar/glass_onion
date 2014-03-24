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
class GlassOnion_Di_Definition
{
    /**
     * @var ReflectionClass
     */
    private $class;

    /**
     * @var array
     */
    private $arguments;

    /**
     * @var boolean
     */
    private $shared;

    /**
     * @var array
     */
    private $invokes;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct($class, array $arguments = array(), $shared = true, $invokes = array())
    {
        if (!isset($class)) {
            throw new InvalidArgumentException('The resource class name is required');
        }
        $this->setClass($class);
        $this->setArguments($arguments);
        $this->setShared($shared);
        $this->setMethodInvokes($invokes);
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param string $class
     * @return GlassOnion_Di_Definition
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @param array $arguments
     * @return GlassOnion_Di_Definition Provides a fluent interface
     */
    public function setArguments(array $arguments)
    {
        if (!is_array($arguments)) {
            throw new InvalidArgumentException();
        }
        $this->arguments = $arguments;
        return $this;
    }

    /**
     * @param mixed $argument
     * @return GlassOnion_Di_Definition Provides a fluent interface
     */
    public function addArgument($argument)
    {
        $this->arguments[] = $argument;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isShared()
    {
        return $this->shared;
    }

    /**
     * @param boolean $shared
     * @return GlassOnion_Di_Definition Provides a fluent interface
     */
    public function setShared($shared)
    {
        if (!is_bool($shared)) {
            throw new InvalidArgumentException();
        }
        $this->shared = $shared;
        return $this;
    }

    /**
     * @return array
     */
    public function getMethodInvokes()
    {
        return $this->invokes;
    }

    /**
     * @param array $invokes
     * @return GlassOnion_Di_Definition Provides a fluent interface
     */
    public function setMethodInvokes(array $invokes)
    {
        if (!is_array($invokes)) {
            throw new InvalidArgumentException();
        }
        $this->resetMethodInvokes();
        foreach ($invokes as $method => $arguments) {
            $this->addMethodInvoke($method, $arguments);
        }
        return $this;
    }

    /**
     * @return GlassOnion_Di_Definition Provides a fluent interface
     */
    public function resetMethodInvokes()
    {
        $this->invokes = array();
        return $this;
    }

    /**
     * @param string $method
     * @param array $arguments
     * @return GlassOnion_Di_Definition Provides a fluent interface
     */
    public function addMethodInvoke($method, array $arguments = array())
    {
        if (!is_string($method) || !is_array($arguments)) {
            throw new InvalidArgumentException();
        }
        $this->invokes[$method] = $arguments;
        return $this;
    }
}