<?php

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
