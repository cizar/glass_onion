<?php

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

        $cmd = sprintf('%s -xml "%s" -xsl "%s" -pdf "%s" 2>&1',
            $this->fop->getBin(),
            $this->getXmlFilename(),
            $this->getXslFilename(),
            $this->pdfFilename
        );

        exec($cmd, $out, $ret);

        if (0 != $ret) {
            require_once 'GlassOnion/Fop/Exception.php';
            throw new GlassOnion_Fop_Exception('Unable to create PDF file');
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
}
