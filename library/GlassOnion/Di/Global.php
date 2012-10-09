<?php

/**
 * @category   GlassOnion
 * @package    GlassOnion_Di
 */
class GlassOnion_Di_Global
{
    /**
     * @var string
     */
    private $id = NULL;

    /**
     * Constructor
     *
     * @param string $id
     */
    public function __construct($id)
    {
        if (!isset($id)) {
            throw new InvalidArgumentException('Resource global identifier is required');
        }
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->id;
    }
}