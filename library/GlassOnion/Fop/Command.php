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
class GlassOnion_Fop_Command
{
    /**
     * @var GlassOnion_Fop
     */
    private $fop = null;

    /**
     * @var string
     */
    private $xmlFilename = null;

    /**
     * @var string
     */
    private $xslFilename = null;

    /**
     * @var string
     */
    private $pdfFilename = null;

    /**
     * @var array
     */
    private $temporaryFiles = array();

    /**
     * Constructor
     *
     * @param GlassOnion_Fop $fop
     * @return void
     */
    public function __construct(GlassOnion_Fop $fop)
    {
        $this->fop = $fop;
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        $this->cleanupTemporaryFiles();
    }

    /**
     * getXml() - Returns the XML contents
     *
     * @return string
     */
    public function getXml()
    {
        return file_get_contents($this->getXmlFilename());
    }

    /**
     * getXmlFilename() - Returns the XML filename
     *
     * @return string
     */
    public function getXmlFilename()
    {
        return $this->xmlFilename;
    }

    /**
     * setXml() - Set the XML contents
     *
     * @param string $xml
     * @return GlassOnion_Fop_Command Provides a fluent interface
     */
    public function setXml($xml)
    {
        return $this->setXmlFilename($this->store($xml));
    }

    /**
     * setXmlFilename() - Set the XML filename
     *
     * @param string $filename
     * @return GlassOnion_Fop_Command Provides a fluent interface
     */
    public function setXmlFilename($filename)
    {
        $this->xmlFilename = $filename;
        return $this;
    }

    /**
     * getXsl() - Returns the XSL contents
     *
     * @return string
     */
    public function getXsl()
    {
        return file_get_contents($this->getXslFilename());
    }

    /**
     * getXslFilename() - Returns the XSL filename
     *
     * @return string
     */
    public function getXslFilename()
    {
        return $this->xslFilename;
    }

    /**
     * setXsl() - Set the XSL contents
     *
     * @param string $xsl
     * @return GlassOnion_Fop_Command Provides a fluent interface
     */
    public function setXsl($xsl)
    {
        return $this->setXslFilename($this->store($xsl));
    }

    /**
     * setXslFilename() - Set the XSL filename
     *
     * @param string $filename
     * @return GlassOnion_Fop_Command Provides a fluent interface
     */
    public function setXslFilename($filename)
    {
        $this->xslFilename = $filename;
        return $this;
    }

    /**
     * getPdf() - Returns the PDF contents
     *
     * @return string
     */
    public function getPdf()
    {
        return file_get_contents($this->pdfFilename);
    }

    /**
     * savePdfTo() - Saves the PDF to a directory
     *
     * @param string $filename
     * @return GlassOnion_Fop_Command Provides a fluent interface
     */
    public function savePdfTo($filename)
    {
        copy($this->pdfFilename, $filename);
        return $this;
    }

    /**
     * execute() - Builds the PDF file
     *
     * @return GlassOnion_Fop_Command Provides a fluent interface
     */
    public function execute()
    {
        $this->pdfFilename = $this->getTempFilename();

        $command = sprintf('%s -xml "%s" -xsl "%s" -pdf "%s" 2>&1',
            $this->fop->getBin(),
            $this->getXmlFilename(),
            $this->getXslFilename(),
            $this->pdfFilename
        );

        exec($command, $output, $status);

        if (0 != $status) {
            require_once 'GlassOnion/Fop/Exception.php';
            throw new GlassOnion_Fop_Exception($this->getFirstExceptionMessage($output));
        }

        return $this;
    }

    /**
     * reset() - Restore the procesor to the initial state
     *
     * @return GlassOnion_Fop_Command Provides a fluent interface
     */
    public function reset()
    {
        $this->xmlFilename = null;
        $this->xslFilename = null;
        $this->pdfFilename = null;
        return $this->cleanupTemporaryFiles();
    }

    /**
     * cleanupTemporaryFiles() - Removes the temporary files
     *
     * @return GlassOnion_Fop_Command Provides a fluent interface
     */
    private function cleanupTemporaryFiles()
    {
        foreach ($this->temporaryFiles as $filename) {
            @unlink($filename);
        }
        $this->temporaryFiles = array();
        return $this;
    }

    /**
     * store() - Helper method that puts a content into a temporary
     * file and returns the filename
     *
     * @param string $content
     * @param string $id
     * @return string
     */
    private function store($content, $id = 'fop')
    {
        $filename = $this->getTempFilename($id);
        file_put_contents($filename, $content);
        return $filename;
    }

    /**
     * getTempFilename() - Returns a valid temporary filename
     *
     * @param string $id
     * @return string
     */
    private function getTempFilename($id = 'fop')
    {
        $filename = tempnam('/tmp', $id);
        $this->temporaryFiles[] = $filename;
        return $filename;
    }

    /**
     * getFirstExceptionMessage() - Looks for the first exception message of the fop command error response
     *
     * @return string
     */    
    private function getFirstExceptionMessage($fopOutput)
    {
        foreach ($fopOutput as $line) {
            if (preg_match('/Exception: (.*)/', $line, $matches)) {
                return $matches[1];
            }
        }
        return "Unknown cause";
    }
}
