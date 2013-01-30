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
