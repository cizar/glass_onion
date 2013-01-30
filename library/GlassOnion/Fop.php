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
 * @package    GlassOnion_Fop
 */
class GlassOnion_Fop
{
    /**
     * @var string
     */
    private $bin = '/usr/bin/fop';

    /**
     * @var string
     */
    private $tempDir = '/tmp';

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
        if (null !== $options) {
            $this->setOptions($options);
        }
    }

    /**
     * setOptions() - Set options en masse
     *
     * @param array|Zend_Config $options
     * @return GlassOnion_Fop Provides a fluent interface
     */
    public function setOptions($options)
    {
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        }

        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (method_exists($this, $method)) {
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
        return $this->bin;
    }

    /**
     * setBin() - Set the FOP bin
     *
     * @param string $bin
     * @return GlassOnion_Fop Provides a fluent interface
     */
    public function setBin($bin)
    {
        if (!file_exists($bin)) {
            require_once 'GlassOnion/Fop/Exception.php';
            throw new GlassOnion_Fop_Exception('The FOP bin does not exists');
        }
        if (!is_executable($bin)) {
            require_once 'GlassOnion/Fop/Exception.php';
            throw new GlassOnion_Fop_Exception('The FOP bin is not executable');
        }
        $this->bin = $bin;
        return $this;
    }

    /**
     * getTempDir() - Returns the directory for temporary files
     *
     * @return string
     */
    public function getTempDir()
    {
        return $this->tempDir;
    }

    /**
     * setTempDir() - Set the directory for temprary files
     *
     * @param string $dir
     * @return GlassOnion_Fop Provides a fluent interface
     */
    public function setTempDir($dir)
    {
        if (!is_dir($dir)) {
            require_once 'GlassOnion/Fop/Exception.php';
            throw new GlassOnion_Fop_Exception('The directory for temporary files is invalid');
        }
        if (!is_writable($dir)) {
            require_once 'GlassOnion/Fop/Exception.php';
            throw new GlassOnion_Fop_Exception('The directory for temporary files is not writable');
        }
        $this->tempDir = $dir;
        return $this;
    }
}
