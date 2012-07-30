<?php

/**
 * @see GlassOnion_Di
 */
require_once 'GlassOnion/Di.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_Di
 */
class GlassOnion_Di_Loader
{
    /**
     * The YAML container factory
     *
     * @return GlassOnion_Di_Container
     */
    public static function fromYaml($filename)
    {
        require_once 'sfYaml.php';
        $config = sfYaml::load($filename);

        $container = GlassOnion_Di::createContainer()->setParameters($config['parameters']);

        foreach ($config['resources'] as $reference => $resource)
        {
            $definition = GlassOnion_Di::createDefinition($resource['class']);

            if (isset($resource['arguments'])) {
                $arguments = self::parseArguments($resource['arguments']);
                $definition->setArguments($arguments);
            }

            if (isset($resource['shared'])) {
                $shared = in_array(strtolower($resource['shared']), array('true', 'yes'));
                $definition->setShared($shared);
            }

            if (isset($resource['invokes'])) {
                foreach ($resource['invokes'] as $method => $arguments) {
                    $arguments = self::parseArguments($arguments);
                    $definition->addMethodInvoke($method, $arguments);
                }
            }

            $container->addDefinition($reference, $definition);
        }

        return $container;
    }

    /**
     * @param array|mixed $arguments
     * @return array|mixed
     */
    private static function parseArguments($arguments)
    {
        if (is_array($arguments)) {
            return array_map(array('self', 'parseArguments'), $arguments);
        }
        if (preg_match('/^@([a-z][a-z0-9_]*)$/i', $arguments, $matches)) {
            return GlassOnion_Di::createReference($matches[1]);
        }
        if (preg_match('/^%([a-z][a-z0-9_.]*)$/i', $arguments, $matches)) {
            return GlassOnion_Di::createParameter($matches[1]);
        }
        if (preg_match('/^\$([a-z][a-z0-9_]*)$/i', $arguments, $matches)) {
            return GlassOnion_Di::createGlobal($matches[1]);
        }
        return $arguments;
    }
}
