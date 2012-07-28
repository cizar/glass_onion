<?php

/**
 * @see GlassOnion_Fop_Exception
 */
require_once 'GlassOnion/Fop/Exception.php';

/**
 * @category   GlassOnion
 * @package    GlassOnion_Fop
 */
class GlassOnion_Fop_Command
{
    /**
     * @var GlassOnion_Fop
     */
    private $_fop = null;

    /**
     * @var string
     */
    private $_xmlFilename = null;

    /**
     * @var string
     */
    private $_xslFilename = null;

    /**
     * @var string
     */
    private $_pdfFilename = null;

    /**
     * @var array
     */
    private $_temporaryFiles = array();

    /**
     * Constructor
     *
     * @param GlassOnion_Fop $fop
     * @return void
     */
    public function __construct(GlassOnion_Fop $fop)
    {
        $this->_fop = $fop;
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        $this->_cleanupTemporaryFiles();
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
        return $this->_xmlFilename;
    }

    /**
     * setXml() - Set the XML contents
     *
     * @param string $xml
     * @return GlassOnion_Fop_Command Provides a fluent interface
     */
    public function setXml($xml)
    {
        return $this->setXmlFilename($this->_store($xml));
    }

    /**
     * setXmlFilename() - Set the XML filename
     *
     * @param string $filename
     * @return GlassOnion_Fop_Command Provides a fluent interface
     */
    public function setXmlFilename($filename)
    {
        $this->_xmlFilename = $filename;
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
        return $this->_xslFilename;
    }

    /**
     * setXsl() - Set the XSL contents
     *
     * @param string $xsl
     * @return GlassOnion_Fop_Command Provides a fluent interface
     */
    public function setXsl($xsl)
    {
        return $this->setXslFilename($this->_store($xsl));
    }

    /**
     * setXslFilename() - Set the XSL filename
     *
     * @param string $filename
     * @return GlassOnion_Fop_Command Provides a fluent interface
     */
    public function setXslFilename($filename)
    {
        $this->_xslFilename = $filename;
        return $this;
    }

    /**
     * getPdf() - Returns the PDF contents
     *
     * @return string
     */
    public function getPdf()
    {
        return file_get_contents($this->_pdfFilename);
    }

    /**
     * savePdfTo() - Saves the PDF to a directory
     *
     * @param string $filename
     * @return GlassOnion_Fop_Command Provides a fluent interface
     */
    public function savePdfTo($filename)
    {
        copy($this->_pdfFilename, $filename);
        return $this;
    }

    /**
     * execute() - Builds the PDF file
     *
     * @return GlassOnion_Fop_Command Provides a fluent interface
     */
    public function execute()
    {
        $this->_pdfFilename = $this->_getTempFilename();

        $cmd = sprintf('%s -xml "%s" -xsl "%s" -pdf "%s" 2>&1',
            $this->_fop->getBin(),
            $this->getXmlFilename(),
            $this->getXslFilename(),
            $this->_pdfFilename
        );

        exec($cmd, $out, $ret);

        if (0 != $ret)
        {
            throw new GlassOnion_Fop_Exception('Oops!, ret: ' . $ret . ' ' . implode(' ', $out) . ' ' . $cmd);
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
        $this->_xmlFilename = null;
        $this->_xslFilename = null;
        $this->_pdfFilename = null;
        $this->_cleanupTemporaryFiles();
    }

    /**
     * _cleanupTemporaryFiles() - Removes the temporary files
     *
     * @return GlassOnion_Fop_Command Provides a fluent interface
     */
    private function _cleanupTemporaryFiles()
    {
        foreach ($this->_temporaryFiles as $filename)
        {
            @unlink($filename);
        }
        $this->_temporaryFiles = array();
        return $this;
    }

    /**
     * _store() - Helper method that puts a content into a temporary
     * file and returns the filename
     *
     * @param string $content
     * @param string $id
     * @return string
     */
    private function _store($content, $id = 'fop')
    {
        $filename = $this->_getTempFilename($id);
        file_put_contents($filename, $content);
        return $filename;
    }

    /**
     * _getTempFilename() - Returns a valid temporary filename
     *
     * @param string $id
     * @return string
     */
    private function _getTempFilename($id = 'fop')
    {
        $filename = tempnam('/tmp', $id);
        $this->_temporaryFiles[] = $filename;
        return $filename;
    }
}
