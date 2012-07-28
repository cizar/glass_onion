<?php

/**
 * @see GlassOnion_Fop_Exception
 */
require_once 'GlassOnion/Fop/Exception.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_Fop
 */
class GlassOnion_Fop
{
    /**
     * @var string
     */
    private $_bin  = '/usr/bin/fop';

    /**
     * @var string
     */
    private $_tempDir = '/tmp';

    /**
     * Factory
     *
     * @param array|Zend_Config $options
     * @return GlassOnion_Fop_Command
     */
    public static function factory($options)
    {
        return new GlassOnion_Fop_Command(new self($options));
    }

    /**
     * Constructor
     *
     * @param array|Zend_Config|null $options
     * @return void
     */
    private function __construct($options = null)
    {
        if (null !== $options)
        {
            $this->setOptions($options);
        }
    }

    /**
     * setOptions() - Set options en masse
     *
     * @param array|Zend_Config $options
     * @return void
     */
    public function setOptions($options)
    {
        if ($options instanceof Zend_Config)
        {
            $options = $options->toArray();
        }

        foreach ($options as $key => $value)
        {
            $method = 'set' . ucfirst($key);
            if (method_exists($this, $method))
            {
                $this->$method($value);
            }
        }
        return $this; 
    }

    /**
     * getBin() - Returns the FOP bin
     *
     * @return string
     */
    public function getBin()
    {
        return $this->_bin;
    }

    /**
     * setBin() - Set the FOP bin
     *
     * @param string $bin
     * @return GlassOnion_Fop Provides a fluent interface
     */
    public function setBin($bin)
    {
        if (!file_exists($bin))
        {
            throw new GlassOnion_Fop_Exception('The FOP bin does not exists');
        }
        if (!is_executable($bin))
        {
            throw new GlassOnion_Fop_Exception('The FOP bin is not executable');
        }
        $this->_bin = $bin;
        return $this;
    }

    /**
     * getTempDir() - Returns the directory for temporary files
     *
     * @return string
     */
    public function getTempDir()
    {
        return $this->_tempDir;
    }

    /**
     * setTempDir() - Set the directory for temprary files
     *
     * @param string $dir
     * @return GlassOnion_Fop Provides a fluent interface
     */
    public function setTempDir($dir)
    {
        if (!is_dir($dir))
        {
            throw new GlassOnion_Fop_Exception('The directory for temporary files is invalid');
        }
        if (!is_writable($dir))
        {
            throw new GlassOnion_Fop_Exception('The directory for temporary files is not writable');
        }
        $this->_tempDir = $dir;
        return $this;
    }
}
