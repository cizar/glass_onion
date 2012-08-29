<?php

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
     * @return void
     */
    public function init()
    {
        $config = $this->getOptions();

        Zend_Loader_Autoloader::getInstance()
            ->pushAutoloader(array('Doctrine_Core', 'autoload'))
            ->pushAutoloader(array('Doctrine_Core', 'modelsAutoload'));

        $manager = Doctrine_Manager::getInstance();

        $attributes = array(
            Doctrine_Core::ATTR_AUTO_ACCESSOR_OVERRIDE => TRUE,
            Doctrine_Core::ATTR_MODEL_LOADING          => Doctrine_Core::MODEL_LOADING_CONSERVATIVE,
            Doctrine_Core::ATTR_AUTOLOAD_TABLE_CLASSES => TRUE,
            Doctrine_Core::ATTR_VALIDATE               => Doctrine_Core::VALIDATE_ALL,
            Doctrine_Core::ATTR_USE_DQL_CALLBACKS      => TRUE,
            Doctrine_Core::ATTR_QUOTE_IDENTIFIER       => TRUE,
        );

        foreach ($attributes as $key => $value)
        {
            $manager->setAttribute($key, $value);
        }

        Doctrine_Core::loadModels($config['models_path']);

        if (array_key_exists('extension_path', $config))
        {
            if (!is_dir($config['extension_path']))
                throw new Exception('Doctrine extension_path not a directory');

            Doctrine_Core::setExtensionsPath($config['extension_path']);
        }

        if (array_key_exists('extension', $config))
        {
            Zend_Loader_Autoloader::getInstance()
                ->pushAutoloader(array('Doctrine_Core', 'extensionsAutoload'));

            foreach ($config['extension'] as $extension => $path)
            {
                $manager->registerExtension($extension,
                    empty($path) ? null : $path);
            }
        }

        $manager->openConnection($config['dsn']);

        return $manager;
    }
}
