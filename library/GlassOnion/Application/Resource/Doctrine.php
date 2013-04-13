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
 * @see Doctrine_Core
 */
require_once 'Doctrine/Core.php';

/**
 * @see Zend_Application_Resource_ResourceAbstract
 */
require_once 'Zend/Application/Resource/ResourceAbstract.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_Application
 */
class GlassOnion_Application_Resource_Doctrine
    extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * Defined by Zend_Application_Resource_Resource
     *
     * @return Doctrine_Manager
     */
    public function init()
    {
        $config = $this->getOptions();

        Zend_Loader_Autoloader::getInstance()
            ->pushAutoloader(array('Doctrine_Core', 'autoload'))
            ->pushAutoloader(array('Doctrine_Core', 'modelsAutoload'));

        $manager = Doctrine_Manager::getInstance();

        $managerAttributes = array(
            Doctrine_Core::ATTR_DEFAULT_TABLE_CHARSET  => 'utf8',
            Doctrine_Core::ATTR_DEFAULT_TABLE_COLLATE  => 'utf8_unicode_ci',
            Doctrine_Core::ATTR_AUTO_ACCESSOR_OVERRIDE => true,
            Doctrine_Core::ATTR_MODEL_LOADING          => Doctrine_Core::MODEL_LOADING_CONSERVATIVE,
            Doctrine_Core::ATTR_AUTOLOAD_TABLE_CLASSES => true,
            Doctrine_Core::ATTR_VALIDATE               => Doctrine_Core::VALIDATE_ALL,
            Doctrine_Core::ATTR_USE_DQL_CALLBACKS      => true,
            Doctrine_Core::ATTR_QUOTE_IDENTIFIER       => true,
        );

        foreach ($managerAttributes as $key => $value) {
            $manager->setAttribute($key, $value);
        }

        Doctrine_Core::loadModels($config['models_path']);

        if (array_key_exists('extension_path', $config)) {
            if (!is_dir($config['extension_path'])) {
                require_once 'Zend/Application/Resource/Exception.php';
                throw new Zend_Application_Resource_Exception('Doctrine extension_path not a directory');
            }

            Doctrine_Core::setExtensionsPath($config['extension_path']);
        }

        if (array_key_exists('extension', $config)) {
            Zend_Loader_Autoloader::getInstance()
                ->pushAutoloader(array('Doctrine_Core', 'extensionsAutoload'));

            foreach ($config['extension'] as $extension => $path) {
                $manager->registerExtension($extension,
                    empty($path) ? null : $path);
            }
        }

        $connection = $manager->openConnection($config['dsn']);

        $connection->setCharset('utf8');

        return $manager;
    }
}
