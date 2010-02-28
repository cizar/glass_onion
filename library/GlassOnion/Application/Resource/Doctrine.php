<?php

class GlassOnion_Application_Resource_Doctrine extends  Zend_Application_Resource_ResourceAbstract
{
    /**
     * Defined by Zend_Application_Resource_Resource
     *
     * @return void
     */
    public function init()
    {
        $config = $this->getOptions();

        require_once 'Doctrine.php';

        Zend_Loader_Autoloader::getInstance()
                ->pushAutoloader(array('Doctrine', 'autoload'));

        spl_autoload_register(array('Doctrine', 'modelsAutoload'));

        $attributes = array(
                Doctrine::ATTR_AUTO_ACCESSOR_OVERRIDE => TRUE,
                Doctrine::ATTR_MODEL_LOADING          => Doctrine::MODEL_LOADING_CONSERVATIVE,
                Doctrine::ATTR_AUTOLOAD_TABLE_CLASSES => TRUE,
                Doctrine::ATTR_VALIDATE               => Doctrine::VALIDATE_ALL,
                Doctrine::ATTR_USE_DQL_CALLBACKS      => TRUE,
                Doctrine::ATTR_QUOTE_IDENTIFIER       => TRUE,
        );

        $manager = Doctrine_Manager::getInstance();

        foreach ($attributes as $key => $value)
                $manager->setAttribute($key, $value);

	Doctrine::loadModels($config['models_path']);

        $manager->openConnection($config['connection_string']);

        return $manager;
    }
}
